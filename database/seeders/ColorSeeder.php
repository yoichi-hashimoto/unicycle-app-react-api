<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [[
            'id'=>1,
            'name'=>'しろ',
            'color_path'=>'#FFFFFF'
        ],[
            'id'=>2,
            'name'=>'みずいろ',
            'color_path'=>'#00FFFF',
        ],[
            'id'=>3,
            'name'=>'きいろ',
            'color_path'=>'#FFFF00',
        ],[
            'id'=>4,
            'name'=>'きみどり',
            'color_path'=>'#99FF99',
        ],[
            'id'=>5,
            'name'=>'ぴんく',
            'color_path'=>'#FF99CC'
        ],[
            'id'=>6,
            'name'=>'おれんじ',
            'color_path'=>'#FF9933',
        ],[
            'id'=>7,
            'name'=>'くろ',
            'color_path'=>'#000000'
        ],[
            'id'=>8,
            'name'=>'そらグラデーション',
            'color_path'=>'linear-gradient(135deg, #dff6ff 0%, #7dd3fc 100%)',
        ],[
            'id'=>9,
            'name'=>'ゆうやけグラデーション',
            'color_path'=>'linear-gradient(135deg, #fff1b8 0%, #ff9a8b 55%, #ff6a88 100%)',
        ],[
            'id'=>10,
            'name'=>'もりグラデーション',
            'color_path'=>'linear-gradient(135deg, #e8ffd8 0%, #7bd389 100%)',
        ]];

        Color::upsert($colors, ['id'], ['name', 'color_path']);
    }
}
