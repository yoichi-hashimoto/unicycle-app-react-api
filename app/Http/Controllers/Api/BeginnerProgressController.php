<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class BeginnerProgressController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'step' => ['required', 'integer', 'between:1,6'],
        ]);

        $user = $request->user();
        $user->beginner_mode = $validated['enabled'];
        $user->beginner_step = $validated['enabled']
            ? max((int) $user->beginner_step, (int) $validated['step'])
            : 1;
        $user->save();

        return new UserResource($user->fresh());
    }
}
