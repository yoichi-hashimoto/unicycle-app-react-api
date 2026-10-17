<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BeginnerProgressController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'step' => ['required', 'integer', 'between:1,6'],
        ]);

        $user = $request->user();
        if ($validated['enabled'] && $user->beginner_mode
            && $validated['step'] > $user->beginner_step + 1) {
            throw ValidationException::withMessages([
                'step' => '初心者コースは一つずつ進めてください。',
            ]);
        }
        $user->beginner_mode = $validated['enabled'];
        $user->beginner_step = $validated['enabled']
            ? max((int) $user->beginner_step, (int) $validated['step'])
            : 1;
        $user->save();

        return new UserResource($user->fresh());
    }
}
