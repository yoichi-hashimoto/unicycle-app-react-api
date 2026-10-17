<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PracticeAttendance extends Model
{
    protected $fillable = ['practice_session_id', 'user_id', 'status'];
    public function user() { return $this->belongsTo(User::class); }
}
