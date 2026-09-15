<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Skill;

class SkillTip extends Model
{
    protected $fillable = [
        'skill_id','user_id','text',
    ];

    public function skill (){
        return $this->belongsTo(Skill::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
