<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Like;

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

        Like::firstOrCreate([
            'challenge_id' => $challenge->id,
            'from_user_id' => $request->user()->id,
        ], [
            'user_id' => $challenge->user_id,
        ]);

        return response()->json([], 201);
    }

}
