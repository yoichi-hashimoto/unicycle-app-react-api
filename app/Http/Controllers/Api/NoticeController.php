<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController
{
    public function index(){
        return Notice::latest(5);}

    public function store(Request $request){
        $validate = $request->validate([
            'title'=>'required|string|max:16',
            'text'=>'required|string|max:200',
        ]);

        $notice = new Notice;

        $notice->title = $validate['title'];
        $notice->text = $validate['text'];

        $notice->save();

        return response()->json($notice,201);
    }
}
