<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Animal;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Animal::insert([[
                'name' => 'ひよこ',
                'avatar_path' => './images/animals/stand_chick.webp',
                'avatar_path_walk' => './images/animals/walk_chick.webp',
                'required_level' => 1,
            ],[
                'name' => 'りす',
                'avatar_path' => './images/animals/stand_squirrel.webp',
                                'avatar_path_walk' => './images/animals/walk_squirrel.webp',
                'required_level' => 6,
            ],[
                'name' => 'うさぎ',
                'avatar_path' => './images/animals/stand_rabbit.webp',
                                'avatar_path_walk' => './images/animals/walk_rabbit.webp',
                'required_level' => 11,
            ],[
                'name' => 'きつね',
                'avatar_path' => './images/animals/stand_fox.webp',
                                'avatar_path_walk' => './images/animals/walk_fox.webp',
                'required_level' => 16,
            ],[
                'name' => 'とら',
                'avatar_path' => './images/animals/stand_tiger.webp',
                                'avatar_path_walk' => './images/animals/walk_tiger.webp',
                'required_level' => 21,
            ],[
                'name' => 'くま',
                'avatar_path' => './images/animals/stand_bear.webp',
                'avatar_path_walk' => './images/animals/walk_bear.webp',
                'required_level' => 26,
            ]]);
    }
}
