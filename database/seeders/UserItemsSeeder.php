<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserItem;

class UserItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserItem::insert([[
            'user_id' => 1,
            'item_id' => 1,
            'is_equipped'=>false
        ],[
            'user_id' => 1,
            'item_id' => 2,
            'is_equipped'=>true
        ],[
            'user_id' => 1,
            'item_id' => 3,
            'is_equipped'=>false
        ],[
            'user_id' => 4,
            'item_id' => 4,
            'is_equipped'=>false
        ],[
            'user_id' => 1,
            'item_id' => 5,
            'is_equipped'=>false
        ],[
            'user_id' => 3,
            'item_id' =>3,
            'is_equipped' =>true
        ],[
            'user_id' =>4,
            'item_id' =>2,
            'is_equipped' =>true
        ]]);
    }
}
