<?php
namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Like;
use App\Models\Notice;
use App\Models\Point;
use App\Models\PracticeSession;
use App\Models\Skill;
use App\Models\SkillTip;
use App\Models\SkillTipLike;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommunityFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function skill(int $level = 1): Skill
    {
        return Skill::create(['name' => '練習技'.$level, 'category' => '基礎', 'required_level' => $level,
            'avatar_path' => '/skill.webp', 'description' => '前を見る', 'point' => 0]);
    }

    private function practice(bool $future = false): PracticeSession
    {
        $notice = Notice::create(['title' => '練習会', 'text' => '体育館で練習', 'category' => '練習会のお知らせ']);
        return $notice->practice()->create(['starts_at' => $future ? now()->addDay() : now()->subDay(), 'location' => '体育館']);
    }

    public function test_all_five_notice_categories_can_be_posted_and_invalid_categories_are_rejected(): void
    {
        Sanctum::actingAs(User::factory()->admin()->create());
        foreach (Notice::CATEGORIES as $category) {
            $response = $this->postJson('/api/notices', ['title' => 'お知らせ', 'text' => '確認してください', 'category' => $category,
                'starts_at' => now()->addDay()->toIso8601String(), 'location' => '体育館']);
            $response->assertCreated()->assertJsonPath('category', $category);
            if ($category === '練習会のお知らせ') $response->assertJsonPath('practice.location', '体育館');
            else $response->assertJsonPath('practice', null);
        }
        $this->getJson('/api/notices')->assertOk()->assertJsonCount(5);
        $this->postJson('/api/notices', ['title' => '通知', 'text' => '内容', 'category' => '不明'])
            ->assertUnprocessable()->assertJsonValidationErrors('category');
        $this->postJson('/api/notices', ['title' => '通知', 'text' => '内容', 'category' => '練習会のお知らせ'])
            ->assertUnprocessable()->assertJsonValidationErrors(['starts_at', 'location']);
        $this->assertDatabaseCount('practice_sessions', 1);
    }

    public function test_practice_attendance_awards_once_and_corrections_adjust_the_same_point_entry(): void
    {
        $member = User::factory()->create();
        $practice = $this->practice();
        Sanctum::actingAs(User::factory()->admin()->create());
        $url = "/api/practices/{$practice->id}/attendance/{$member->id}";
        foreach (['attending' => 0, 'attended' => 1] as $status => $points) {
            $this->putJson($url, ['status' => $status])->assertOk()->assertJsonPath('earned_points', $points);
        }
        $this->putJson($url, ['status' => 'attended'])->assertOk()->assertJsonPath('earned_points', 1);
        $this->putJson($url, ['status' => 'absent'])->assertOk()->assertJsonPath('earned_points', 0);
        $this->putJson($url, ['status' => 'attended'])->assertOk()->assertJsonPath('earned_points', 1);
        $this->getJson('/api/practices')->assertOk()->assertJsonPath('0.attendances.0.status', 'attended');
        $this->assertDatabaseCount('practice_attendances', 1);
        $this->assertDatabaseCount('points', 1);
        $second = $this->practice();
        $this->putJson("/api/practices/{$second->id}/attendance/{$member->id}", ['status' => 'attended'])->assertOk()->assertJsonPath('earned_points', 2);
    }

    public function test_future_practices_guardians_and_unauthorized_attendance_changes_do_not_award_points(): void
    {
        $member = User::factory()->create();
        $guardian = User::factory()->guardian()->create();
        $practice = $this->practice(true);
        $url = "/api/practices/{$practice->id}/attendance/{$member->id}";
        Sanctum::actingAs($member);
        $this->getJson('/api/practices')->assertForbidden();
        $this->putJson($url, ['status' => 'attended'])->assertForbidden();
        $this->postJson('/api/notices', ['title' => '通知', 'text' => '内容', 'category' => 'その他'])->assertForbidden();
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->putJson($url, ['status' => 'attended'])->assertUnprocessable();
        $this->putJson($url, ['status' => 'invalid'])->assertUnprocessable();
        $this->putJson("/api/practices/{$practice->id}/attendance/{$guardian->id}", ['status' => 'attending'])->assertUnprocessable();
        $this->assertDatabaseCount('points', 0);
    }

    public function test_comments_support_nested_replies_and_reject_a_parent_from_another_skill(): void
    {
        $member = User::factory()->create();
        $other = User::factory()->create();
        $skill = $this->skill();
        $root = SkillTip::create(['user_id' => $other->id, 'skill_id' => $skill->id, 'text' => '前を見よう']);
        Sanctum::actingAs($member);
        $reply = $this->postJson("/api/skill/{$skill->id}/tips", ['text' => 'ありがとう', 'parent_id' => $root->id])
            ->assertCreated()->assertJsonPath('parent_id', $root->id)->json('id');
        $this->postJson("/api/skill/{$skill->id}/tips", ['text' => '続けよう', 'parent_id' => $reply])->assertCreated();
        $otherSkill = $this->skill(2);
        $this->postJson("/api/skill/{$otherSkill->id}/tips", ['text' => '違う技', 'parent_id' => $root->id])
            ->assertUnprocessable()->assertJsonValidationErrors('parent_id');
        $this->getJson("/api/skill/{$skill->id}/tips")->assertOk()->assertJsonCount(3);
        $this->getJson('/api/skills')->assertOk()->assertJsonPath('0.unread_tip_count', 1);
    }

    public function test_combined_challenge_and_comment_hearts_reward_each_hundred_once(): void
    {
        $owner = User::factory()->create();
        $donors = User::factory()->count(100)->create();
        $skill = $this->skill();
        $challenge = Challenge::create(['user_id' => $owner->id, 'skill_id' => $skill->id, 'success_score' => 1]);
        $tip = SkillTip::create(['user_id' => $owner->id, 'skill_id' => $skill->id, 'text' => '前を見よう']);
        Like::insert($donors->take(99)->map(fn ($donor) => ['challenge_id' => $challenge->id, 'user_id' => $owner->id, 'from_user_id' => $donor->id, 'created_at' => now(), 'updated_at' => now()])->all());
        $this->assertSame(99, $owner->received_likes);
        $this->assertSame(0, (int) $owner->earned_points);
        Sanctum::actingAs($donors[99]);
        $this->postJson("/api/skill-tips/{$tip->id}/likes")->assertCreated()->assertJsonPath('received_likes', 1)->assertJsonPath('is_liked_by_me', true);
        $this->assertSame(100, $owner->received_likes);
        $this->assertSame(5, (int) $owner->earned_points);
        $this->postJson("/api/skill-tips/{$tip->id}/likes")->assertCreated();
        $this->assertSame(100, $owner->received_likes);
        $this->assertSame(5, (int) $owner->earned_points);
        SkillTipLike::insert($donors->take(99)->map(fn ($donor) => ['skill_tip_id' => $tip->id, 'user_id' => $owner->id, 'from_user_id' => $donor->id, 'created_at' => now(), 'updated_at' => now()])->all());
        $this->assertSame(199, $owner->received_likes);
        $this->postJson('/api/likes', ['challenge_id' => $challenge->id])->assertCreated();
        $this->assertSame(200, $owner->received_likes);
        $this->assertSame(10, (int) $owner->earned_points);
        $this->postJson('/api/likes', ['challenge_id' => $challenge->id])->assertCreated();
        $this->assertSame(10, (int) $owner->earned_points);
        $this->assertDatabaseCount('points', 2);
        $this->getJson('/api/users')->assertOk()->assertJsonPath('data.0.received_likes', 200);
        Sanctum::actingAs($owner);
        $this->getJson('/api/user')->assertOk()->assertJsonPath('user.received_likes', 200)->assertJsonPath('user.earned_points', 10);
        $this->getJson('/api/profile/activity')->assertOk()->assertJsonPath('statistics.received_likes', 200)->assertJsonPath('statistics.heart_reward_points', 10);
    }

    public function test_self_hearts_and_guardian_writes_are_rejected(): void
    {
        $member = User::factory()->create();
        $skill = $this->skill();
        $challenge = Challenge::create(['user_id' => $member->id, 'skill_id' => $skill->id, 'success_score' => 1]);
        $tip = SkillTip::create(['user_id' => $member->id, 'skill_id' => $skill->id, 'text' => '前を見る']);
        Sanctum::actingAs($member);
        $this->postJson('/api/likes', ['challenge_id' => $challenge->id])->assertUnprocessable();
        $this->postJson("/api/skill-tips/{$tip->id}/likes")->assertUnprocessable();
        Sanctum::actingAs(User::factory()->guardian()->create());
        $this->postJson("/api/skill-tips/{$tip->id}/likes")->assertForbidden();
        $this->postJson("/api/skill/{$skill->id}/tips", ['text' => '返信', 'parent_id' => $tip->id])->assertForbidden();
        $this->assertDatabaseCount('points', 0);
    }

    public function test_profile_statistics_count_distinct_cleared_skills_and_all_attempts_with_a_time_history(): void
    {
        $member = User::factory()->create(['created_at' => now()->subDays(10)]);
        $first = $this->skill(); $second = $this->skill(2);
        foreach ([[$first, 1], [$first, 3], [$first, 3], [$second, 3]] as $index => [$skill, $score]) {
            $challenge = Challenge::create(['user_id' => $member->id, 'skill_id' => $skill->id, 'success_score' => $score]);
            $challenge->created_at = now()->subDays(8 - $index); $challenge->save();
        }
        $other = User::factory()->create();
        Challenge::create(['user_id' => $other->id, 'skill_id' => $first->id, 'success_score' => 3]);
        Sanctum::actingAs($member);
        $this->getJson('/api/profile/activity')->assertOk()
            ->assertJsonPath('statistics.challenge_count', 4)->assertJsonPath('statistics.cleared_skill_count', 2)
            ->assertJsonPath('level_history.0.level', 1)->assertJsonPath('level_history.1.level', 2)
            ->assertJsonPath('level_history.2.level', 3)->assertJsonPath('level_history.3.level', 3)
            ->assertJsonCount(4, 'level_history');
    }
}
