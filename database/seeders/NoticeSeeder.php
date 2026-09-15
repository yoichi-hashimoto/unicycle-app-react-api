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
        Notice::insert([[
            'title'=>'アイテム追加',
            'text'=>'新アイテム「ふえ」が追加されました！'
        ],[
            'title'=>'システムアップデート',
            'text'=>'2026年8月30日12：00～0：00でアップデートを行いますので、システムを開くことはできません。'
        ]]);
    }
}
