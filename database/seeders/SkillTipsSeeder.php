<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkillTip;

class SkillTipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SkillTip::insert([
        [
            'skill_id' =>6,
            'user_id' =>3,
            'text'=>'足元を見ずに遠くを見たら落ち着いて出来ました！',
            'created_at'=>'2026-09-01',
        ],[
            'skill_id' =>11,
            'user_id' => 1,
            'text' =>'軸足を強めに踏み込むと一輪車がしっかりと立って乗りやすいです！',
            'created_at'=>'2026-09-02',
        ],[
            'skill_id' =>13,
            'user_id' =>2,
            'text' =>'前かがみにならないように注意！',
            'created_at'=>'2026-09-03',
        ],    
        ]);
    }
}
