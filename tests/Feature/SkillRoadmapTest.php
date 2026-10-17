<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Point;
use App\Models\Skill;
use App\Models\User;
use App\Support\SkillRoadmapCatalog;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SkillRoadmapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SkillSeeder::class);
    }

    private function skill(string $name): Skill { return Skill::where('name', $name)->firstOrFail(); }

    private function completeBasics(User $user): void
    {
        foreach (Skill::where('category', '基礎')->get() as $skill) {
            Challenge::create(['user_id' => $user->id, 'skill_id' => $skill->id, 'success_score' => 3]);
        }
        $user->refresh();
    }

    private function record(User $member, Skill $skill, int|string $score = 3, array $extra = [])
    {
        return $this->postJson('/api/challenges', array_merge([
            'user_id' => $member->id, 'skill_id' => $skill->id, 'success_score' => $score,
        ], $extra));
    }

    public function test_existing_fifty_skills_are_partitioned_into_a_basic_path_and_six_genres(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->getJson('/api/skills')->assertOk()->assertJsonCount(50)->json();
        $skills = collect($response);
        $this->assertSame(25, $skills->where('is_basic', true)->count());
        foreach (array_combine(SkillRoadmapCatalog::GENRES, [3, 2, 5, 3, 2, 10]) as $genre => $count) {
            $this->assertSame($count, $skills->where('category', $genre)->count());
        }
        $this->assertSame(1, $skills->where('is_current', true)->first()['required_level']);
        $this->assertSame(0, $skills->where('is_basic', false)->where('is_available', true)->count());
        $this->assertSame(8, \Illuminate\Support\Facades\DB::table('skill_prerequisites')->count());
    }

    public function test_goal_selection_requires_basic_completion_and_survives_reload_per_account(): void
    {
        $member = User::factory()->create(); $other = User::factory()->create();
        $spin = $this->skill('右スピン3周');
        Sanctum::actingAs($member);
        $this->patchJson('/api/user/skill-target', ['skill_id' => $spin->id])->assertUnprocessable();
        $this->completeBasics($member);
        $this->patchJson('/api/user/skill-target', ['skill_id' => $spin->id])->assertOk()->assertJsonPath('data.target_skill.id', $spin->id);
        Sanctum::actingAs($member->fresh());
        $this->getJson('/api/user')->assertOk()->assertJsonPath('user.target_skill.name', '右スピン3周')->assertJsonPath('user.current_level', 26);
        $skills = collect($this->getJson('/api/skills')->assertOk()->json());
        $this->assertTrue($skills->firstWhere('id', $spin->id)['is_current']);
        $this->assertNull($other->fresh()->target_skill_id);
        $this->patchJson('/api/user/skill-target', ['skill_id' => null])->assertOk()->assertJsonPath('data.target_skill', null);
    }

    public function test_basic_tests_are_sequential_and_client_supplied_points_are_ignored(): void
    {
        $member = User::factory()->create();
        Sanctum::actingAs(User::factory()->admin()->create());
        $first = Skill::where('category', '基礎')->where('required_level', 1)->firstOrFail();
        $second = Skill::where('category', '基礎')->where('required_level', 2)->firstOrFail();
        $this->record($member, $second)->assertUnprocessable();
        $this->record($member, $first, 2, ['earned_point' => 9999])->assertCreated()->assertJsonPath('points_awarded', 0)->assertJsonPath('user.current_level', 1);
        $this->record($member, $first, '3', ['earned_point' => 9999])->assertCreated()->assertJsonPath('points_awarded', 0)->assertJsonPath('user.current_level', 2);
        $this->assertSame(0, (int) $member->fresh()->earned_points);
        $this->assertDatabaseCount('points', 0);
    }

    public function test_basic_levels_follow_required_level_instead_of_database_ids(): void
    {
        Skill::query()->delete();
        $first = new Skill(['name' => '基礎1', 'category' => '基礎', 'required_level' => 1, 'avatar_path' => '/one.webp', 'description' => '基礎']);
        $first->id = 101; $first->save();
        $second = new Skill(['name' => '基礎2', 'category' => '基礎', 'required_level' => 2, 'avatar_path' => '/two.webp', 'description' => '基礎']);
        $second->id = 500; $second->save();
        $member = User::factory()->create();
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->record($member, $second)->assertUnprocessable();
        $this->record($member, $first)->assertCreated()->assertJsonPath('user.current_level', 2);
        $this->record($member, $second)->assertCreated()->assertJsonPath('user.current_level', 3);
    }

    public function test_retesting_a_cleared_skill_does_not_lower_level_or_repeat_rewards(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        Sanctum::actingAs(User::factory()->admin()->create());
        $first = Skill::where('required_level', 1)->firstOrFail();
        $this->record($member, $first, 0)->assertCreated()->assertJsonPath('user.current_level', 26)->assertJsonPath('points_awarded', 0);
        Sanctum::actingAs($member->fresh());
        $response = $this->getJson('/api/profile/activity')->assertOk()->assertJsonPath('statistics.cleared_skill_count', 25);
        $this->assertSame(26, collect($response->json('level_history'))->last()['level']);
        $this->assertTrue(collect($this->getJson('/api/skills')->json())->firstWhere('id', $first->id)['is_cleared']);
    }

    public function test_jumping_branches_unlock_from_five_hops_and_failed_attempts_remain_available(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        Sanctum::actingAs(User::factory()->admin()->create());
        $three = $this->skill('ホッピング3回'); $five = $this->skill('ホッピング5回');
        $this->record($member, $five)->assertUnprocessable();
        $this->record($member, $three, 1)->assertCreated()->assertJsonPath('points_awarded', 0);
        $this->assertContains($three->id, $member->fresh()->remain_skills['next_skill']->pluck('id')->all());
        $this->record($member, $three)->assertCreated()->assertJsonPath('points_awarded', 2);
        $this->record($member, $five)->assertCreated()->assertJsonPath('points_awarded', 3);
        $available = $member->fresh()->remain_skills['next_skill']->pluck('id')->all();
        foreach (['ホッピング10回', '前進ホッピング3m', 'ホッピング90度ターン'] as $name) $this->assertContains($this->skill($name)->id, $available);
        $this->assertSame(5, (int) $member->fresh()->earned_points);
    }

    public function test_freestyle_goals_can_cross_genres_and_are_cleared_on_first_success(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        $spin = $this->skill('右スピン3周'); $foot = $this->skill('片足走行5m');
        Sanctum::actingAs($member);
        $this->patchJson('/api/user/skill-target', ['skill_id' => $spin->id])->assertOk();
        $this->patchJson('/api/user/skill-target', ['skill_id' => $foot->id])->assertOk()->assertJsonPath('data.target_skill.category', '片足系');
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->record($member, $foot, 3, ['earned_point' => 999])->assertCreated()->assertJsonPath('points_awarded', 2)->assertJsonPath('user.target_skill', null);
        $this->record($member, $foot, 3, ['earned_point' => 999])->assertCreated()->assertJsonPath('points_awarded', 0);
        $this->assertSame(2, (int) $member->fresh()->earned_points);
        $this->assertDatabaseCount('points', 1);
        Sanctum::actingAs($member->fresh());
        $this->patchJson('/api/user/skill-target', ['skill_id' => $foot->id])->assertUnprocessable();
    }

    public function test_left_and_right_spins_are_independent_options(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->record($member, $this->skill('左スピン3周'))->assertCreated()->assertJsonPath('points_awarded', 2);
        $this->assertContains($this->skill('右スピン3周')->id, $member->fresh()->remain_skills['next_skill']->pluck('id')->all());
    }

    public function test_pair_performance_requires_two_pair_skills_and_pair_hops_require_solo_hops(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        Sanctum::actingAs(User::factory()->admin()->create());
        $performance = $this->skill('30秒ペア演技');
        $this->record($member, $performance)->assertUnprocessable();
        $this->record($member, $this->skill('同時ホッピング5回'))->assertUnprocessable();
        $this->record($member, $this->skill('ペア並走1周'))->assertCreated();
        $this->record($member, $performance)->assertUnprocessable();
        $this->record($member, $this->skill('手つなぎ1周'))->assertCreated();
        $this->record($member, $performance)->assertCreated()->assertJsonPath('points_awarded', 3);
        $this->assertSame(7, (int) $member->fresh()->earned_points);
    }

    public function test_read_states_and_progress_belong_to_the_signed_in_user_only(): void
    {
        $first = User::factory()->create(); $other = User::factory()->create();
        $this->completeBasics($other);
        Challenge::create(['user_id' => $other->id, 'skill_id' => $this->skill('右スピン3周')->id, 'success_score' => 3]);
        Sanctum::actingAs($first);
        $response = collect($this->getJson('/api/skills')->assertOk()->json());
        $this->assertSame(0, $response->where('is_cleared', true)->count());
        $this->assertSame(1, $response->where('is_available', true)->count());
    }

    public function test_guardians_and_non_admins_cannot_write_test_results(): void
    {
        $member = User::factory()->create(); $guardian = User::factory()->guardian()->create();
        Sanctum::actingAs($member);
        $this->record($member, $this->skill('右スピン3周'))->assertForbidden();
        Sanctum::actingAs($guardian);
        $this->patchJson('/api/user/skill-target', ['skill_id' => $this->skill('右スピン3周')->id])->assertForbidden();
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->record($guardian, $this->skill('右スピン3周'))->assertUnprocessable();
        $this->record($member, $this->skill('右スピン3周'), 4)->assertUnprocessable();
        $this->assertDatabaseCount('points', 0);
    }

    public function test_existing_awarded_history_and_balances_are_preserved_by_catalog_reapplication(): void
    {
        $member = User::factory()->create(); $this->completeBasics($member);
        $spin = $this->skill('右スピン3周');
        Challenge::create(['user_id' => $member->id, 'skill_id' => $spin->id, 'success_score' => 3]);
        Point::create(['user_id' => $member->id, 'points' => 2]);
        $this->seed(SkillSeeder::class);
        $this->assertDatabaseCount('skills', 50);
        $this->assertDatabaseCount('challenges', 26);
        Sanctum::actingAs(User::factory()->admin()->create());
        $this->record($member, $spin)->assertCreated()->assertJsonPath('points_awarded', 0);
        $this->assertSame(2, (int) $member->fresh()->earned_points);
        $this->assertDatabaseCount('points', 1);
    }
}
