<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ItemSeeder::class,        
            ColorSeeder::class,
            AvatarSeeder::class,
            AnimalSeeder::class,
            // UserItemsSeeder::class,
            SkillSeeder::class,
            // ChallengeSeeder::class,
            // LikeSeeder::class,
            NoticeSeeder::class,
            // PointSeeder::class,
            // SkillTipsSeeder::class,
        ]);
    }
}
