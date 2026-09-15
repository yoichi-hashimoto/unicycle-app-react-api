<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'skill_id' => $this->skill_id,
            'success_score' => $this->success_score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_name' => $this->user_name,
            'user_avatar_path' => $this->user_avatar_path,
            'current_animal' => $this->current_animal,
            'background_color' =>$this->user?->background_color,
            'skill_name' => $this->skill_name,
            'current_level' => $this->current_level,
            'received_likes' => $this->received_likes,
            'from_user_id' => $this->likes()->pluck('from_user_id'),
            'is_liked_by_me'=> $this->likes()
            ->where('from_user_id',$request->user()?->id)
            ->exists(),
            'debug_auth' => $request->user()?->id,
            'color_path' => $this->color_path,
            'equipped_item_path' => $this->equipped_item_path,
        ];
    }
}
