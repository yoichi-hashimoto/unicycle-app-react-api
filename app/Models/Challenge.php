<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserItem;
use App\Models\Skill;

class Challenge extends Model
{
    protected $fillable = ['user_id','skill_id','success_score','earned_point'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    public function getUserAvatarPathAttribute()
    {
        return $this->user ? $this->user->avatar_path : null;
    }

    public function getCurrentAnimalAttribute()
    {
        return $this->user && $this->user->current_animal->avatar_path ? $this->user->current_animal->avatar_path : null;
    }

    public function getColorPathAttribute(){
        $color = Color::where('id',$this->user->color_id)->first();
        return $color ? $color->color_path : null;
    }

    public function getSkillNameAttribute()
    {
        return $this->skill ? $this->skill->name : null;
    }

    public function getCurrentLevelAttribute()
    {
        return $this->skill ? $this->skill->required_level : null;
    }

    public function getReceivedLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getEquippedItemPathAttribute(){
        return $this->user->equipped_item_path ? $this->user->equipped_item_path : null ;

    }
}
