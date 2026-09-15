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
                'avatar_path' => './images/animals/stand_chick.png',
                'avatar_path_walk' => './images/animals/walk_chick.png',
                'required_level' => 1,
            ],[
                'name' => 'りす',
                'avatar_path' => './images/animals/stand_squirrel.png',
                                'avatar_path_walk' => './images/animals/walk_squirrel.png',
                'required_level' => 6,
            ],[
                'name' => 'うさぎ',
                'avatar_path' => './images/animals/stand_rabbit.png',
                                'avatar_path_walk' => './images/animals/walk_rabbit.png',
                'required_level' => 11,
            ],[
                'name' => 'きつね',
                'avatar_path' => './images/animals/stand_fox.png',
                                'avatar_path_walk' => './images/animals/walk_fox.png',
                'required_level' => 16,
            ],[
                'name' => 'とら',
                'avatar_path' => './images/animals/stand_tiger.png',
                                'avatar_path_walk' => './images/animals/walk_tiger.png',
                'required_level' => 21,
            ],[
                'name' => 'くま',
                'avatar_path' => './images/animals/stand_bear.png',
                'avatar_path_walk' => './images/animals/walk_bear.png',
                'required_level' => 26,
            ]]);
    }
}
