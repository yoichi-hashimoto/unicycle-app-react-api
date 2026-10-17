<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Like;
use App\Models\User;
use App\Services\HeartRewards;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
        public function index()
    {
        return view('/');
    }

    public function store(Request $request)
    {
        $validated = $request-> validate([
            'challenge_id' => ['required', 'integer', 'exists:challenges,id'],
        ]);

        $challenge = Challenge::findOrFail($validated['challenge_id']);

        abort_if($challenge->user_id === $request->user()->id, 422, '自分のチャレンジには❤を送れません。');
        DB::transaction(function () use ($challenge, $request) {
            $recipient = User::whereKey($challenge->user_id)->lockForUpdate()->firstOrFail();
            Like::firstOrCreate([
                'challenge_id' => $challenge->id,
                'from_user_id' => $request->user()->id,
            ], ['user_id' => $recipient->id]);
            HeartRewards::award($recipient);
        });

        return response()->json([], 201);
    }

}
