<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    public const CATEGORIES = ['メンバー更新', 'クラブ更新', '練習会のお知らせ', 'システム変更', 'その他'];
    protected $fillable = [
        'title','text','category'
    ];
    public function practice() { return $this->hasOne(PracticeSession::class); }
}
