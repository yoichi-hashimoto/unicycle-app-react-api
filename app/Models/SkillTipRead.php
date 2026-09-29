<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillTipRead extends Model
{
    protected $fillable = ['user_id', 'skill_id', 'last_read_at'];

    protected function casts(): array
    {
        return [
            'last_read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
