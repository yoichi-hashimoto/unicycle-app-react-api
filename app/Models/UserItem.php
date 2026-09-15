<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class UserItem extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'is_equipped'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
