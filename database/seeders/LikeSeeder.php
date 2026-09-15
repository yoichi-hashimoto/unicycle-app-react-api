<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Like;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Like::insert([[
            'user_id' => 1,
            'from_user_id' => 1,
            'challenge_id' => 3,
        ],[
            'user_id' => 2,
            'from_user_id' => 2,
            'challenge_id' => 1,
        ],[
            'user_id' => 1,
            'from_user_id' => 2,
            'challenge_id' => 3,
        ],[
            'user_id' => 2,
            'from_user_id' => 3,
            'challenge_id' => 1,
        ],[
            'user_id' => 5,
            'from_user_id' => 3,
            'challenge_id' => 2,
        ],[
            'user_id' => 2,
            'from_user_id' => 4,
            'challenge_id' => 1,
        ],[
            'user_id' => 5,
            'from_user_id' => 4,
            'challenge_id' => 2,
        ],[
            'user_id' => 1,
            'from_user_id' => 4,
            'challenge_id' => 3,
        ],[
            'user_id' => 2,
            'from_user_id' => 5,
            'challenge_id' => 1,
        ],[
            'user_id' => 5,
            'from_user_id' => 5,
            'challenge_id' => 2,
        ],[
            'user_id' => 1,
            'from_user_id' => 5,
            'challenge_id' => 3,
        ]]);
    }
}
