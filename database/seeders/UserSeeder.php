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
            'name' => '橋本千穂',
            'login_id'=>'U-0001',
            'password' => Hash::make('OUC0123'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '橋本菜央',
            'login_id'=>'U-0002',
            'password' => Hash::make('OUC1234'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '橋本結城',
            'login_id'=>'U-0003',
            'password' => Hash::make('OUC2345'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '橋本陽一',
            'login_id'=>'U-0004',
            'password' => Hash::make('OUC3456'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => true,
            'last_seen_animal_id'=>1
        ],[
            'name' => '川村慶',
            'login_id'=>'U-0005',
            'password' => Hash::make('OUC4567'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '川村優子',
            'login_id'=>'U-0006',
            'password' => Hash::make('OUC5678'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '山田ひかり',
            'login_id'=>'U-0007',
            'password' => Hash::make('OUC6789'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '田中晴紀',
            'login_id'=>'U-0008',
            'password' => Hash::make('OUC7890'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '田中明香',
            'login_id'=>'U-0009',
            'password' => Hash::make('OUC8901'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '千葉りー',
            'login_id'=>'U-0010',
            'password' => Hash::make('OUC9012'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '齊藤佳玲奈',
            'login_id'=>'U-0011',
            'password' => Hash::make('OUC9876'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '島川愛心',
            'login_id'=>'U-0012',
            'password' => Hash::make('OUC8765'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '山口和深',
            'login_id'=>'U-0013',
            'password' => Hash::make('OUC7654'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '山口仁深',
            'login_id'=>'U-0014',
            'password' => Hash::make('OUC6543'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '佐々田徠夢',
            'login_id'=>'U-0015',
            'password' => Hash::make('OUC5432'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '佐藤杏姫',
            'login_id'=>'U-0016',
            'password' => Hash::make('OUC4321'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '猪狩傑',
            'login_id'=>'U-0017',
            'password' => Hash::make('OUC3210'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ],[
            'name' => '猪狩彩香',
            'login_id'=>'U-0018',
            'password' => Hash::make('OUC2109'),
            'user_avatar_id' => 1,
            'color_id' => 1,
            'is_admin' => false,
            'last_seen_animal_id'=>1
        ]

        ]);
    }
}
