<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = ['name', 'avatar_path','avatar_path_walk', 'required_level'];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function remainLevel($currentLevel)
    {
        return $this->required_level - $currentLevel;
    }
}
