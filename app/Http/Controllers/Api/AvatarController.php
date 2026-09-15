<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\UserAvatar;

class AvatarController extends Controller
{
    public function index(){
        return UserAvatar::all();
    }

    public function store(){

    }
}