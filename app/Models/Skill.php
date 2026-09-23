<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SkillTip;


class Skill extends Model
{
    protected $fillable = ['name', 'description','avatar_path','movie_path','required_level','animal_id','point','category'];

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function skillTips(){
        return $this->hasMany(SkillTip::class,'skill_id');
    }

}
