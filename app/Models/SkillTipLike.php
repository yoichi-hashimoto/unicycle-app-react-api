<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SkillTipLike extends Model
{
    protected $fillable = ['skill_tip_id', 'user_id', 'from_user_id'];
    public function tip() { return $this->belongsTo(SkillTip::class, 'skill_tip_id'); }
}
