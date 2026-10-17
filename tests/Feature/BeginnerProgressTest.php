<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BeginnerProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_reload_preserve_the_egg_until_progress_is_completed(): void
    {
        $user = User::factory()->create(['beginner_mode' => true, 'beginner_step' => 1]);
        $this->postJson('/login', ['login_id' => $user->login_id, 'password' => 'password'])->assertOk();
        $this->assertAuthenticatedAs($user);
        $this->withHeader('Origin', 'http://localhost:3000');
        foreach (range(1, 2) as $_) {
            $this->getJson('/api/user')->assertOk()
                ->assertJsonPath('user.beginner_step', 1)
                ->assertJsonPath('user.display_animal.avatar_path', './images/animals/beginner/stage-01-egg.webp');
        }
    }

    public function test_an_existing_beginner_cannot_be_hatched_by_a_stale_completed_progress_update(): void
    {
        $user = User::factory()->create(['beginner_mode' => true, 'beginner_step' => 1]);
        Sanctum::actingAs($user);
        $this->patchJson('/api/user/beginner-progress', ['enabled' => true, 'step' => 6])
            ->assertUnprocessable()->assertJsonValidationErrors('step');
        $this->assertSame(1, $user->fresh()->beginner_step);
    }

    public function test_each_completion_advances_one_stage_and_only_stage_six_hatches(): void
    {
        Animal::create([
            'name' => 'ひよこ', 'avatar_path' => './images/animals/stand_chick.webp',
            'avatar_path_walk' => './images/animals/walk_chick.webp', 'required_level' => 1,
        ]);
        $user = User::factory()->create(['beginner_mode' => true, 'beginner_step' => 1]);
        Sanctum::actingAs($user);
        $paths = [
            2 => 'stage-02-small-crack.webp', 3 => 'stage-03-large-crack.webp',
            4 => 'stage-04-peeking.webp', 5 => 'stage-05-limbs.webp',
        ];
        foreach (range(2, 6) as $step) {
            $response = $this->patchJson('/api/user/beginner-progress', ['enabled' => true, 'step' => $step]);
            $response->assertOk()->assertJsonPath('data.beginner_step', $step)
                ->assertJsonPath('data.display_animal.name', $step < 6 ? 'たまご' : 'ひよこ')
                ->assertJsonPath('data.display_animal.avatar_path', $step < 6
                    ? './images/animals/beginner/'.$paths[$step]
                    : './images/animals/stand_chick.webp');
        }
        // Another browser with an older stage must not rewind the account.
        $this->patchJson('/api/user/beginner-progress', ['enabled' => true, 'step' => 2])
            ->assertOk()->assertJsonPath('data.beginner_step', 6);
    }

    public function test_a_new_course_can_import_guest_progress_without_modifying_another_account(): void
    {
        $other = User::factory()->create(['beginner_mode' => true, 'beginner_step' => 1]);
        $new = User::factory()->create(['beginner_mode' => false]);
        Sanctum::actingAs($new);
        $this->patchJson('/api/user/beginner-progress', ['enabled' => true, 'step' => 5])
            ->assertOk()->assertJsonPath('data.beginner_step', 5);
        $this->assertSame(1, $other->fresh()->beginner_step);
    }
}
