<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->lexify('??????'),
            'login_id' => fake()->unique()->bothify('OUC####'),
            'password' => static::$password ??= Hash::make('password'),
            'user_avatar_id' => null,
            'color_id' => null,
            'role' => 'member',
            'beginner_mode' => false,
            'beginner_step' => 1,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function guardian(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'gurdian',
        ]);
    }
}
