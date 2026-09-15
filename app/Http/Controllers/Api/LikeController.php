<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
        public function index()
    {
        return view('/');
    }

    public function store(Request $request, Like $like)
    {
        $validated = $request-> validate([
        'challenge_id' => 'integer',
        'user_id' => 'integer',
        'from_user_id' => 'integer',
        ]);

        Like::firstOrCreate([
    'challenge_id' => $validated['challenge_id'],
    'from_user_id' => $validated['from_user_id'],
], [
    'user_id' => $validated['user_id'],
]);

        return response()->json();
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
