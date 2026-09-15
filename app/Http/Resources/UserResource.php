<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Challenge;
use App\Models\Skill;
use App\Models\Animal;
use App\Models\Like;
use App\Models\User;
use App\Models\Color;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'background_color'=>$this->background_color,
            'login_id'=>$this->login_id,
            'is_admin'=>$this->is_admin,
            'avatar_path'=>$this->avatar_path,
            'skill_name'=>$this->skill_name,
            'received_likes' => $this->received_likes,
            'current_level' => $this->current_level,
            'avatar_path_walk'=>$this->avatar_path_walk,
            'remain_level' => $this->remain_level,
            'success_score' => $this->success_score,
            'color_path' => $this->color_path,
            'equipped_item_path' => $this->equipped_item_path,
            'earned_points'=>$this->earned_points,
            'user_items' =>$this->user_item,
            'current_animal' => $this->current_animal,
            'remain_skills'=>$this->remain_skills,
            'last_seen_animal'=>$this->lastSeenAnimal,
        ];
    }
}
