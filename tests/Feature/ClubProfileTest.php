<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\Challenge;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClubProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register_a_club_and_existing_profiles_can_remain_unassigned(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);
        $this->postJson('/api/users', [
            'name' => '保護者', 'login_id' => 'CLUB0001', 'role' => 'gurdian',
            'password' => 'password123', 'password_confirmation' => 'password123',
            'club_name' => 'おおくま一輪車クラブ',
        ])->assertCreated()->assertJsonPath('user.club_name', 'おおくま一輪車クラブ');
        $this->assertDatabaseHas('users', ['login_id' => 'CLUB0001', 'club_name' => 'おおくま一輪車クラブ']);
        $this->assertNull($admin->fresh()->club_name);
    }

    public function test_profile_club_can_be_changed_preserved_and_cleared_without_affecting_others(): void
    {
        $member = User::factory()->create(['club_name' => 'あおぞらクラブ']);
        $other = User::factory()->create(['club_name' => 'ひまわりクラブ']);
        Sanctum::actingAs($member);
        $this->patchJson("/api/users/{$member->id}", ['club_name' => 'さくらクラブ'])
            ->assertOk()->assertJsonPath('data.club_name', 'さくらクラブ');
        $this->patchJson("/api/users/{$member->id}", ['name' => 'なまえ'])
            ->assertOk()->assertJsonPath('data.club_name', 'さくらクラブ');
        $this->getJson('/api/user')->assertOk()->assertJsonPath('user.club_name', 'さくらクラブ');
        $this->patchJson("/api/users/{$member->id}", ['club_name' => null])
            ->assertOk()->assertJsonPath('data.club_name', null);
        $this->assertSame('ひまわりクラブ', $other->fresh()->club_name);
    }

    public function test_invalid_club_names_are_rejected_without_changing_the_saved_club(): void
    {
        $member = User::factory()->create(['club_name' => 'あおぞらクラブ']);
        Sanctum::actingAs($member);
        foreach ([str_repeat('あ', 101), ['invalid']] as $invalid) {
            $this->patchJson("/api/users/{$member->id}", ['club_name' => $invalid])
                ->assertUnprocessable()->assertJsonValidationErrors('club_name');
        }
        $this->assertSame('あおぞらクラブ', $member->fresh()->club_name);
    }

    public function test_ranking_challenges_and_skill_comments_expose_the_current_club(): void
    {
        $member = User::factory()->create(['club_name' => 'あおぞらクラブ']);
        $skill = Skill::create([
            'name' => '壁で10秒安定', 'category' => '基礎', 'required_level' => 1,
            'avatar_path' => './images/skills/level-01.webp', 'description' => '姿勢を保つ', 'point' => 0,
        ]);
        Challenge::create(['user_id' => $member->id, 'skill_id' => $skill->id, 'success_score' => 1]);
        Sanctum::actingAs($member);
        $this->postJson("/api/skill/{$skill->id}/tips", ['text' => '前を見よう'])
            ->assertCreated()->assertJsonPath('user.club_name', 'あおぞらクラブ');
        $this->getJson('/api/users')->assertOk()->assertJsonPath('data.0.club_name', 'あおぞらクラブ');
        $this->getJson('/api/challenges')->assertOk()->assertJsonPath('data.0.club_name', 'あおぞらクラブ');
        $this->getJson("/api/skill/{$skill->id}/tips")->assertOk()->assertJsonPath('0.user.club_name', 'あおぞらクラブ');
        $this->getJson('/api/skills')->assertOk()->assertJsonPath('0.skill_tips.0.user.club_name', 'あおぞらクラブ');
        $member->update(['club_name' => 'さくらクラブ']);
        $this->getJson('/api/challenges')->assertOk()->assertJsonPath('data.0.club_name', 'さくらクラブ');
        $this->getJson("/api/skill/{$skill->id}/tips")->assertOk()->assertJsonPath('0.user.club_name', 'さくらクラブ');
    }

    public function test_current_rank_and_card_image_follow_all_five_egg_stages(): void
    {
        Animal::create([
            'name' => 'ひよこ', 'avatar_path' => './images/animals/stand_chick.webp',
            'avatar_path_walk' => './images/animals/walk_chick.webp', 'required_level' => 1,
        ]);
        $member = User::factory()->create(['beginner_mode' => true, 'beginner_step' => 1]);
        Sanctum::actingAs($member);
        $stages = ['egg', 'small-crack', 'large-crack', 'peeking', 'limbs'];
        foreach (range(1, 5) as $step) {
            $member->update(['beginner_step' => $step]);
            $path = sprintf('./images/animals/beginner/stage-%02d-%s.webp', $step, $stages[$step - 1]);
            $this->getJson('/api/user')->assertOk()
                ->assertJsonPath('user.current_animal.name', 'たまご')
                ->assertJsonPath('user.current_animal.avatar_path', $path)
                ->assertJsonPath('user.display_animal.avatar_path', $path);
            $this->getJson('/api/users')->assertOk()->assertJsonPath('data.0.current_animal.name', 'たまご');
        }
        $member->update(['beginner_step' => 6]);
        $this->getJson('/api/user')->assertOk()->assertJsonPath('user.current_animal.name', 'ひよこ');
    }
}
