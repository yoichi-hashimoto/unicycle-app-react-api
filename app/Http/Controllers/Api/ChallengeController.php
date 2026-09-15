<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Http\Resources\ChallengeResource;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class ChallengeController extends Controller
{
        public function index()
    {
        return ChallengeResource::collection(Challenge::with(['user', 'skill', 'likes'])->get());
    }

    public function store(Request $request ,Challenge $challenge)
    {
        $validate = $request->validate([
            'user_id' => ["integer"],
            'success_score' => ["integer"],
            'skill_id'=>["integer"],
            'earned_point'=>["integer"],
        ]);

        DB::transaction(function() use($challenge,$validate){

        $challenge->user_id = $validate['user_id'];
        $challenge->skill_id = $validate['skill_id'];
        $challenge->success_score = $validate['success_score'];
        $challenge->save();

        if(!empty ($validate['earned_point'])){
            $point = new Point;

            $point->user_id =$validate['user_id'];
            $point->points = $validate['earned_point'];

            $point->save();
            }
    });

        return response()->json([
            'message'=>'チャレンジを登録しました'
        ],201);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
