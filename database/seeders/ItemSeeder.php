<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::insert([[
            'name'=>'ハチ',
            'required_point'=>10,
            'avatar_path'=>'./images/items/bee_smile.png',
        ],[
            'name'=>'ちょうちょ',
             'required_point'=>10,
            'avatar_path'=>'./images/items/butterfly_smile.png',
        ],[
            'name'=>'はな',
            'required_point'=>10,
            'avatar_path'=>'./images/items/flower_smile.png',
        ],[
            'name'=>'キウイ',
            'required_point'=>10,
            'avatar_path'=>'./images/items/kiwi.png',
        ],[
            'name'=>'月',
            'required_point'=>10,
            'avatar_path'=>'./images/items/moon&star_smile.png',
        ],[
            'name'=>'なし',
            'required_point'=>10,
            'avatar_path'=>'./images/items/none.png',
        ],[
            'name'=>'いちご',
            'required_point'=>10,
            'avatar_path'=>'./images/items/strawberry.png',
        ],[
            'name'=>'トマト',
            'required_point'=>10,
            'avatar_path'=>'./images/items/tomato.png',
        ],[
            'name'=>'おひさま',
            'required_point'=>10,
            'avatar_path'=>'./images/items/sun_smile.png',
        ],[
            'name'=>'UFO',
            'required_point'=>10,
            'avatar_path'=>'./images/items/ufo_smile.png',
        ]]);
    }
}
