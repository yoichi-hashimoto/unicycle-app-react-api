<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SkillTip;
use App\Models\Skill;
use App\Models\SkillTipRead;
use App\Models\SkillTipLike;
use App\Models\User;
use App\Services\HeartRewards;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SkillTipController
{
    public function index($skillId){

        $tips =SkillTip::where('skill_id',$skillId)->with(['user.avatar', 'likes'])->oldest()->orderBy('id')->get();
        return response()->json($tips);
    }   

    public function store(Request $request, Skill $skill){
        $validated = $request->validate([
            'text'=>'required|string|max:255',
            'parent_id' => ['nullable', 'integer', Rule::exists('skill_tips', 'id')->where('skill_id', $skill->id)],
        ]);

        $tip =SkillTip::create([
            'user_id' => $request->user()->id,
            'skill_id'=>$skill->id,
            'text'=>$validated['text'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);
        return response()->json(
            $tip->load(['user.avatar', 'likes']),201
        );
    }

    public function like(Request $request, SkillTip $tip)
    {
        abort_if($tip->user_id === $request->user()->id, 422, '自分のコメントには❤を送れません。');
        DB::transaction(function () use ($request, $tip) {
            $recipient = User::whereKey($tip->user_id)->lockForUpdate()->firstOrFail();
            SkillTipLike::firstOrCreate([
                'skill_tip_id' => $tip->id, 'from_user_id' => $request->user()->id,
            ], ['user_id' => $recipient->id]);
            HeartRewards::award($recipient);
        });
        return response()->json($tip->fresh()->load(['user.avatar', 'likes']), 201);
    }

    public function markRead(Request $request, Skill $skill)
    {
        SkillTipRead::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'skill_id' => $skill->id,
            ],
            ['last_read_at' => now()],
        );

        return response()->noContent();
    }
}
