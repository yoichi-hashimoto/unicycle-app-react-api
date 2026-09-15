<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'avatar_path',
        'required_point',
    ];

    public function users()
    {
        return $this->hasMany(UserItem::class);
    }
}
