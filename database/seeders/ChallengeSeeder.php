<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Challenge::insert([[
            'user_id' => 1,
            'skill_id' => 1,
            'success_score' => 0,
        ],[
            'user_id' => 2,
            'skill_id' => 6,
            'success_score' => 0,
        ],[
            'user_id' => 3,
            'skill_id' => 22,
            'success_score' => 0,
        ],[
            'user_id' => 4,
            'skill_id' => 16,
            'success_score' => 0,
        ],[
            'user_id' => 5,
            'skill_id' => 11,
            'success_score' => 2,
        ]]);
    }
}
