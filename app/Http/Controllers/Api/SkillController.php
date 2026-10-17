<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\SkillTipRead;
use App\Models\User;
use App\Services\SkillRoadmap;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $reads = SkillTipRead::where('user_id', $request->user()->id)
            ->pluck('last_read_at', 'skill_id');

        $skills = Skill::with('prerequisites', 'skillTips.user.avatar', 'skillTips.likes')->orderBy('required_level')->orderBy('id')->get();
        $roadmap = new SkillRoadmap($request->user(), $skills);
        return $skills->each(function (Skill $skill) use ($reads, $request, $roadmap) {
                foreach ($roadmap->state($skill) as $key => $value) $skill->setAttribute($key, $value);
                $lastReadAt = $reads->get($skill->id);
                $skill->unread_tip_count = $skill->skillTips
                    ->where('user_id', '!=', $request->user()->id)
                    ->filter(fn ($tip) => ! $lastReadAt || $tip->created_at->gt($lastReadAt))
                    ->count();
            });
    }

    public function target(Request $request)
    {
        $data = $request->validate(['skill_id' => ['present', 'nullable', 'integer', 'exists:skills,id']]);
        $user = DB::transaction(function () use ($request, $data) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if ($data['skill_id'] !== null) {
                $skill = Skill::with('prerequisites')->findOrFail($data['skill_id']);
                $roadmap = $user->roadmap();
                $reason = $roadmap->lockedReason($skill);
                if ($roadmap->isCleared($skill) || $reason !== null) {
                    throw ValidationException::withMessages(['skill_id' => $reason ?? '達成済みの技は新しい目標に設定できません。']);
                }
            }
            $user->update(['target_skill_id' => $data['skill_id']]);
            return $user->fresh();
        });
        return new UserResource($user);
    }
}
