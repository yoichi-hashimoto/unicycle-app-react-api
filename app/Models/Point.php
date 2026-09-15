<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Point extends Model
{
    protected $fillable = [
'user_id','points','created_at','updated_at',
    ];
    

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function totalPoints(){
        return $this ? $this->select('user_id',DB::raw('SUM(points) as total_points'))
        ->groupBy('user_id')
        ->get()
        : 0;
    }

}
