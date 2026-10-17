<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ProfileActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $challenges = $user->challenges()
            ->with(['skill', 'likes.fromUser.avatar'])
            ->latest()
            ->get();

        $likeEvents = $challenges
            ->flatMap(fn ($challenge) => $challenge->likes->map(fn ($like) => [
                'id' => 'like-'.$like->id,
                'type' => 'like_received',
                'message' => $challenge->skill?->name.'のチャレンジに'.$like->fromUser?->name.'から❤をもらいました！',
                'created_at' => $like->created_at,
            ]));

        $tipEvents = $user->skillTips()
            ->with('skill')
            ->latest()
            ->get()
            ->map(fn ($tip) => [
                'id' => 'tip-'.$tip->id,
                'type' => 'skill_tip',
                'message' => $tip->skill?->name.'のスキルに「'.$tip->text.'」と投稿しました！',
                'created_at' => $tip->created_at,
            ]);

        $lastChallengeAt = $challenges->first()?->created_at;

        $level = 1;
        $history = collect([['at' => $user->created_at->toIso8601String(), 'level' => 1]]);
        foreach ($challenges->sortBy(fn ($challenge) => sprintf('%s-%020d', $challenge->created_at->format('Y-m-d H:i:s.u'), $challenge->id)) as $challenge) {
            if ($challenge->skill_id >= 26) continue;
            $next = $challenge->success_score >= 3 ? min($challenge->skill_id + 1, 26) : $challenge->skill_id;
            if ($next !== $level) {
                $level = $next;
                $history->push(['at' => $challenge->created_at->toIso8601String(), 'level' => $level]);
            }
        }
        $history->push(['at' => now()->toIso8601String(), 'level' => $user->current_level]);

        return response()->json([
            'statistics' => [
                'cleared_skill_count' => $challenges->where('success_score', '>=', 3)->pluck('skill_id')->unique()->count(),
                'challenge_count' => $challenges->count(),
                'received_likes' => $user->received_likes,
                'heart_reward_points' => (int) $user->points()->where('source_key', 'like', 'heart:%')->sum('points'),
            ],
            'level_history' => $history->values(),
            'days_since_last_challenge' => $lastChallengeAt
                ? (int) $lastChallengeAt->copy()->startOfDay()->diffInDays(now()->startOfDay())
                : null,
            'last_challenge_at' => $lastChallengeAt,
            'activities' => $likeEvents
                ->concat($tipEvents)
                ->sortByDesc('created_at')
                ->values(),
            'challenges' => $challenges->map(fn ($challenge) => [
                'id' => $challenge->id,
                'skill_name' => $challenge->skill?->name,
                'success_score' => $challenge->success_score,
                'created_at' => $challenge->created_at,
                'received_likes' => $challenge->likes->count(),
                'like_users' => $challenge->likes->map(fn ($like) => [
                    'id' => $like->fromUser?->id,
                    'name' => $like->fromUser?->name,
                    'avatar_path' => $like->fromUser?->avatar_path,
                ])->filter(fn ($liker) => $liker['id'] !== null)->values(),
            ])->values(),
        ]);
    }
}
