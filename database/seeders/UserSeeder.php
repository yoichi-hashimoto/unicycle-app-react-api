<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([[
            'name' => 'よういち',
            'login_id'=>'OUC3456',
            'password' => Hash::make('test'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => true,
            'last_seen_animal_id'=>1
        ],[
            'name' => 'ゆうき',
            'login_id'=>'OUC2345',
            'password' => Hash::make('test'),
            'user_avatar_id' => 2,
            'color_id' => 2,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => 'ちほ',
            'login_id'=>'OUC0123',
            'password' => Hash::make('test'),
            'user_avatar_id' => 3,
            'color_id' => 3,
            'is_admin' => false,
            'last_seen_animal_id'=>4
        ],[
            'name' => 'なお',
            'login_id'=>'OUC1234',
            'password' => Hash::make('test'),
            'user_avatar_id' => 4,
            'color_id' => 4,
            'is_admin' => false,
            'last_seen_animal_id'=>3
        ],[
            'name' => 'はるき',
            'login_id'=>'OUC7890',
            'password' => Hash::make('test'),
            'user_avatar_id' => 5,
            'color_id' => 5,
            'is_admin' => false,
            'last_seen_animal_id'=>4
        ]]);
    }
}
