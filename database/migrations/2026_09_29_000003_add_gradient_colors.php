<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('colors')->upsert([
            [
                'id' => 8,
                'name' => 'そらグラデーション',
                'color_path' => 'linear-gradient(135deg, #dff6ff 0%, #7dd3fc 100%)',
            ],
            [
                'id' => 9,
                'name' => 'ゆうやけグラデーション',
                'color_path' => 'linear-gradient(135deg, #fff1b8 0%, #ff9a8b 55%, #ff6a88 100%)',
            ],
            [
                'id' => 10,
                'name' => 'もりグラデーション',
                'color_path' => 'linear-gradient(135deg, #e8ffd8 0%, #7bd389 100%)',
            ],
        ], ['id'], ['name', 'color_path']);
    }

    public function down(): void
    {
        DB::table('users')->whereIn('color_id', [8, 9, 10])->update(['color_id' => 1]);
        DB::table('colors')->whereIn('id', [8, 9, 10])->delete();
    }
};
