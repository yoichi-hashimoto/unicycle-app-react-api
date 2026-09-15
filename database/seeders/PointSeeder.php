<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Point;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Point::insert([[
            'user_id'=>1,
            'points'=>3,
        ],[
            'user_id'=>2,
            'points'=>3,
        ],[
            'user_id'=>4,
            'points'=>10,
        ]]);
    }
}
