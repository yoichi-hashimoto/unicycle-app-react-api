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

        $challengeEvents = $challenges->map(fn ($challenge) => [
            'id' => 'challenge-'.$challenge->id,
            'type' => 'challenge',
            'message' => $challenge->skill?->name.'のチャレンジを投稿しました！',
            'created_at' => $challenge->created_at,
        ]);

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

        return response()->json([
            'days_since_last_challenge' => $lastChallengeAt
                ? (int) $lastChallengeAt->copy()->startOfDay()->diffInDays(now()->startOfDay())
                : null,
            'last_challenge_at' => $lastChallengeAt,
            'activities' => $challengeEvents
                ->concat($likeEvents)
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
