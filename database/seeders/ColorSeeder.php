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
        Color::insert([[
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
        ]
        ]);
    }
}
