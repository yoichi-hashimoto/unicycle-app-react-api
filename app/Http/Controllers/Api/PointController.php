<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class PointController
{
    public function index(){
        return Point::select('user_id',DB::raw('SUM(points) as total_points'))
        ->groupBy('user_id')
        ->get();
    }
}
