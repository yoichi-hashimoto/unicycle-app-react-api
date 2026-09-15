<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
        public function index()
    {
        return Skill::with('challenges:id,user_id,skill_id','skillTips.user.avatar')->get();
    }

    public function store(Request $request)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
