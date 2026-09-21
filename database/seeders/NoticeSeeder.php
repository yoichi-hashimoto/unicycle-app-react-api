<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notice;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notice::insert([
            'title'=>'はじめまして！',
            'text'=>'Uni-Circleの世界へようこそ！　キャラクターや背景、パスワードを自分の好きなものに変更して、一輪車の練習をはじめよう！'
        ]);
    }
}
