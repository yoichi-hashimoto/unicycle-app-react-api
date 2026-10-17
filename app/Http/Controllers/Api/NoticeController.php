<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NoticeController
{
    public function index(){
        return Notice::with('practice')->latest()->orderByDesc('id')->take(20)->get();}

    public function store(Request $request){
        $validate = $request->validate([
            'title'=>'required|string|max:16',
            'text'=>'required|string|max:200',
            'category' => ['required', Rule::in(Notice::CATEGORIES)],
            'starts_at' => ['required_if:category,練習会のお知らせ', 'nullable', 'date'],
            'location' => ['required_if:category,練習会のお知らせ', 'nullable', 'string', 'max:100'],
        ]);

        $notice = DB::transaction(function () use ($validate) {
            $notice = Notice::create(collect($validate)->only(['title', 'text', 'category'])->all());
            if ($notice->category === '練習会のお知らせ') {
                $notice->practice()->create(['starts_at' => $validate['starts_at'], 'location' => $validate['location']]);
            }
            return $notice->load('practice');
        });

        return response()->json($notice,201);
    }
}
