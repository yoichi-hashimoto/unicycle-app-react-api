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
        $base = [
            'id' => $this->id,
            'name'=>$this->name,
            'club_name' => $this->club_name,
            'role' => $this->role->value,
        ];

        if (! $this->hasMemberProfile()) {
            return $base;
        }

        return $base + [
            'avatar_path'=>$this->avatar_path,
            'skill_name'=>$this->skill_name,
            'received_likes' => $this->received_likes,
            'current_level' => $this->current_level,
            'target_skill_id' => $this->target_skill_id,
            'target_skill' => $this->roadmap()->target()?->only(['id', 'name', 'category', 'point', 'avatar_path']),
            'basic_cleared_count' => $this->roadmap()->skills->filter(fn ($skill) => \App\Services\SkillRoadmap::isBasic($skill) && $this->roadmap()->isCleared($skill))->count(),
            'advanced_cleared_count' => $this->roadmap()->skills->filter(fn ($skill) => ! \App\Services\SkillRoadmap::isBasic($skill) && $this->roadmap()->isCleared($skill))->count(),
            'avatar_path_walk'=>$this->avatar_path_walk,
            'remain_level' => $this->remain_level,
            'success_score' => $this->success_score,
            'color_path' => $this->color_path,
            'equipped_item_path' => $this->equipped_item_path,
            'earned_points'=>$this->earned_points,
            'user_items' =>$this->user_item,
            'current_animal' => $this->display_animal,
            'display_animal' => $this->display_animal,
            'beginner_mode' => $this->beginner_mode,
            'beginner_step' => $this->beginner_step,
            'remain_skills'=>$this->remain_skills,
            'last_seen_animal'=>$this->lastSeenAnimal,
        ];
    }
}
