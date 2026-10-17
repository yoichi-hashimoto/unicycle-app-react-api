<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $fillable = ['notice_id', 'starts_at', 'location'];
    protected function casts(): array { return ['starts_at' => 'datetime']; }
    public function notice() { return $this->belongsTo(Notice::class); }
    public function attendances() { return $this->hasMany(PracticeAttendance::class); }
}
