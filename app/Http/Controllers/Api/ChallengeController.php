<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Http\Resources\ChallengeResource;
use App\Models\Point;
use Illuminate\Support\Facades\DB;
use App\Models\Skill;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class ChallengeController extends Controller
{
        public function index()
    {
        return ChallengeResource::collection(
            Challenge::with(['user', 'skill', 'likes.fromUser.avatar'])->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'success_score' => ['required', 'integer', 'between:0,3'],
            'skill_id' => ['required', 'integer', 'exists:skills,id'],
        ]);
        $validate['success_score'] = (int) $validate['success_score'];
        return DB::transaction(function () use ($validate) {
            $user = User::whereKey($validate['user_id'])->lockForUpdate()->firstOrFail();
            if ($user->isGuardian()) throw ValidationException::withMessages(['user_id' => '閲覧者は検定を受けられません。']);
            $skill = Skill::with('prerequisites')->findOrFail($validate['skill_id']);
            $roadmap = $user->roadmap();
            if ($reason = $roadmap->lockedReason($skill)) throw ValidationException::withMessages(['skill_id' => $reason]);
            $wasCleared = $roadmap->isCleared($skill);
            $challenge = Challenge::create($validate);
            $awarded = 0;
            if ($validate['success_score'] === 3 && ! $wasCleared && ! \App\Services\SkillRoadmap::isBasic($skill) && $skill->point > 0) {
                $point = Point::firstOrCreate(['source_key' => "skill:{$user->id}:{$skill->id}"], [
                    'user_id' => $user->id, 'points' => (int) $skill->point,
                ]);
                $awarded = $point->wasRecentlyCreated ? (int) $point->points : 0;
            }
            if ($validate['success_score'] === 3 && $user->target_skill_id === $skill->id) $user->update(['target_skill_id' => null]);
            return response()->json([
                'message' => 'チャレンジを登録しました',
                'challenge' => new ChallengeResource($challenge),
                'points_awarded' => $awarded,
                'user' => new UserResource($user->fresh()),
            ], 201);
        });
    }

}
