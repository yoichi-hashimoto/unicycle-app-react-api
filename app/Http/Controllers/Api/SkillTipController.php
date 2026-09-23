<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SkillTip;
use App\Models\Skill;

class SkillTipController
{
    public function index($skillId){

        $tips =SkillTip::where('skill_id',$skillId)->with('user')->latest()->get();
        return response()->json($tips);
    }   

    public function store(Request $request, Skill $skill){
        $validated = $request->validate([
            'text'=>'required|string|max:255'
        ]);

        $tip =SkillTip::create([
            'user_id' => $request->user()->id,
            'skill_id'=>$skill->id,
            'text'=>$validated['text'],
        ]);
        return response()->json(
            $tip->load('user.avatar'),201
        );
    }
}
