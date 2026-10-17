<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Skill;

class SkillTip extends Model
{
    protected $fillable = [
        'skill_id','user_id','text','parent_id',
    ];
    protected $appends = ['received_likes', 'is_liked_by_me'];

    public function likes() { return $this->hasMany(SkillTipLike::class); }
    public function getReceivedLikesAttribute(): int { return $this->likes->count(); }
    public function getIsLikedByMeAttribute(): bool {
        return $this->likes->contains('from_user_id', request()->user()?->id);
    }

    public function skill (){
        return $this->belongsTo(Skill::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
