<?php
namespace App\Http\Controllers\Api;

use App\Models\PracticeSession;
use App\Models\PracticeAttendance;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PracticeController extends Controller
{
    public function index()
    {
        return PracticeSession::with(['notice', 'attendances.user'])->orderByDesc('starts_at')->get();
    }

    public function update(Request $request, PracticeSession $practice, User $user)
    {
        abort_if($user->isGuardian(), 422, '閲覧者には参加ポイントを付与できません。');
        $data = $request->validate(['status' => ['required', Rule::in(['unanswered', 'attending', 'absent', 'attended'])]]);
        if ($data['status'] === 'attended' && $practice->starts_at->isFuture()) {
            throw ValidationException::withMessages(['status' => '開催前は参加予定を選択してください。']);
        }
        $attendance = DB::transaction(function () use ($practice, $user, $data) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $attendance = PracticeAttendance::updateOrCreate([
                'practice_session_id' => $practice->id, 'user_id' => $user->id,
            ], ['status' => $data['status']]);
            // A single ledger entry per session/member makes repeats and corrections idempotent.
            Point::updateOrCreate(['source_key' => "practice:{$practice->id}:{$user->id}"], [
                'user_id' => $user->id, 'points' => $data['status'] === 'attended' ? 1 : 0,
            ]);
            return $attendance;
        });
        return response()->json(['attendance' => $attendance, 'earned_points' => $user->fresh()->earned_points]);
    }
}
