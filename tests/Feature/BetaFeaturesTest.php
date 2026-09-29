<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Like;
use App\Models\Skill;
use App\Models\SkillTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BetaFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardian_can_browse_but_cannot_write_member_data(): void
    {
        $guardian = User::factory()->guardian()->create();
        $owner = User::factory()->create();
        $skill = $this->createSkill();
        $challenge = Challenge::create([
            'user_id' => $owner->id,
            'skill_id' => $skill->id,
            'success_score' => 1,
        ]);

        Sanctum::actingAs($guardian);

        $this->getJson('/api/users')->assertOk();
        $this->getJson('/api/challenges')->assertOk();
        $this->getJson('/api/skills')->assertOk();

        $this->postJson('/api/likes', [
            'challenge_id' => $challenge->id,
        ])->assertForbidden();

        $this->getJson('/api/profile/activity')->assertForbidden();
    }

    public function test_admin_can_create_a_profileless_guardian(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $this->postJson('/api/users', [
            'name' => '保護者',
            'login_id' => 'GRD0001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'gurdian',
        ])->assertCreated()
            ->assertJsonPath('user.role', 'gurdian');

        $guardian = User::where('login_id', 'GRD0001')->firstOrFail();
        $this->assertNull($guardian->user_avatar_id);
        $this->assertNull($guardian->color_id);
    }

    public function test_beginner_progress_changes_the_display_animal_to_an_egg(): void
    {
        $member = User::factory()->create();
        Sanctum::actingAs($member);

        $this->patchJson('/api/user/beginner-progress', [
            'enabled' => true,
            'step' => 4,
        ])->assertOk()
            ->assertJsonPath('data.beginner_step', 4)
            ->assertJsonPath('data.display_animal.name', 'たまご')
            ->assertJsonPath(
                'data.display_animal.avatar_path',
                './images/animals/beginner/stage-04-peeking.webp',
            );
    }

    public function test_skill_comment_unread_count_is_cleared_when_opened(): void
    {
        $reader = User::factory()->create();
        $writer = User::factory()->create();
        $skill = $this->createSkill();
        SkillTip::create([
            'user_id' => $writer->id,
            'skill_id' => $skill->id,
            'text' => '目線を上げる',
        ]);

        Sanctum::actingAs($reader);

        $this->getJson('/api/skills')
            ->assertOk()
            ->assertJsonPath('0.unread_tip_count', 1);

        $this->patchJson("/api/skill/{$skill->id}/tips/read")->assertNoContent();

        $this->getJson('/api/skills')
            ->assertOk()
            ->assertJsonPath('0.unread_tip_count', 0);
    }

    public function test_profile_activity_contains_challenges_likes_and_comments(): void
    {
        $member = User::factory()->create(['name' => '本人']);
        $supporter = User::factory()->create(['name' => '応援者']);
        $skill = $this->createSkill();
        $challenge = Challenge::create([
            'user_id' => $member->id,
            'skill_id' => $skill->id,
            'success_score' => 3,
        ]);
        Like::create([
            'user_id' => $member->id,
            'from_user_id' => $supporter->id,
            'challenge_id' => $challenge->id,
        ]);
        SkillTip::create([
            'user_id' => $member->id,
            'skill_id' => $skill->id,
            'text' => '前を見る',
        ]);

        Sanctum::actingAs($member);

        $this->getJson('/api/profile/activity')
            ->assertOk()
            ->assertJsonPath('days_since_last_challenge', 0)
            ->assertJsonPath('challenges.0.received_likes', 1)
            ->assertJsonPath('challenges.0.like_users.0.name', '応援者')
            ->assertJsonFragment(['type' => 'like_received'])
            ->assertJsonFragment(['type' => 'skill_tip']);
    }

    private function createSkill(): Skill
    {
        return Skill::create([
            'name' => '壁で10秒安定',
            'category' => '基礎',
            'required_level' => 1,
            'avatar_path' => './images/skills/level-01.webp',
            'description' => '壁につかまって姿勢を保つ',
            'point' => 0,
        ]);
    }
}
