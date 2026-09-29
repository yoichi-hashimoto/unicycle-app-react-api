<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\SkillTipRead;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $reads = SkillTipRead::where('user_id', $request->user()->id)
            ->pluck('last_read_at', 'skill_id');

        return Skill::with('challenges:id,user_id,skill_id', 'skillTips.user.avatar')
            ->get()
            ->each(function (Skill $skill) use ($reads, $request) {
                $lastReadAt = $reads->get($skill->id);
                $skill->unread_tip_count = $skill->skillTips
                    ->where('user_id', '!=', $request->user()->id)
                    ->filter(fn ($tip) => ! $lastReadAt || $tip->created_at->gt($lastReadAt))
                    ->count();
            });
    }
}
