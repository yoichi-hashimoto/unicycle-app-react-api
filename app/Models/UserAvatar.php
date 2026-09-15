<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserAvatar extends Model
{
    protected $fillable = ['id','avatar_path'];

    public function users(){
    return $this->hasMany(User::class);}

}
