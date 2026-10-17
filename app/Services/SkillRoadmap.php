<?php

namespace App\Services;

use App\Models\Skill;
use App\Models\User;
use App\Support\SkillRoadmapCatalog;
use Illuminate\Support\Collection;

class SkillRoadmap
{
    public Collection $skills;
    public Collection $challenges;
    public Collection $clearedIds;
    private int $level = 1;
    private Collection $latestBySkill;

    public function __construct(public User $user, ?Collection $skills = null)
    {
        $this->skills = $skills ?? Skill::with('prerequisites')->get();
        $this->challenges = $user->challenges()->orderBy('id')->get();
        $this->clearedIds = $this->challenges->where('success_score', '>=', 3)->pluck('skill_id')->map(fn ($id) => (int) $id)->unique();
        $this->latestBySkill = $this->challenges->keyBy('skill_id');
        $completed = array_fill_keys($this->skills->filter(fn ($skill) => self::isBasic($skill) && $this->isCleared($skill))->pluck('required_level')->all(), true);
        while ($this->level <= 25 && isset($completed[$this->level])) $this->level++;
    }

    public static function isBasic(Skill $skill): bool
    {
        return $skill->category === '基礎' && $skill->required_level >= 1 && $skill->required_level <= 25;
    }

    public function currentLevel(): int
    {
        return $this->level;
    }

    public function isCleared(Skill $skill): bool { return $this->clearedIds->contains((int) $skill->id); }

    public function lockedReason(Skill $skill): ?string
    {
        if ($this->user->isGuardian()) return '閲覧専用アカウントです。';
        if ($this->isCleared($skill)) return null;
        if (self::isBasic($skill)) {
            return (int) $skill->required_level === $this->currentLevel() ? null : '基礎の技は現在のレベルから順番に挑戦してください。';
        }
        if ($this->currentLevel() < 26) return '基礎25技をクリアすると挑戦できます。';
        $missing = $skill->prerequisites->reject(fn ($required) => $this->isCleared($required));
        if ($missing->isNotEmpty()) return $missing->pluck('name')->join('・').'に合格すると挑戦できます。';
        if ($skill->required_pair_skills > 0) {
            $pairCount = $this->skills->filter(fn ($candidate) => $candidate->category === 'ペア・演技系' && $candidate->id !== $skill->id && $this->isCleared($candidate))->count();
            if ($pairCount < $skill->required_pair_skills) return 'ペア・演技系の技を'.$skill->required_pair_skills.'つ以上達成すると挑戦できます。';
        }
        return null;
    }

    public function target(): ?Skill
    {
        $skill = $this->skills->firstWhere('id', $this->user->target_skill_id);
        return $skill && ! $this->isCleared($skill) && $this->lockedReason($skill) === null ? $skill : null;
    }

    public function currentSkill(): ?Skill
    {
        if ($this->currentLevel() < 26) return $this->skills->first(fn ($skill) => self::isBasic($skill) && (int) $skill->required_level === $this->currentLevel());
        return $this->target();
    }

    public function state(Skill $skill): array
    {
        $reason = $this->lockedReason($skill);
        $cleared = $this->isCleared($skill);
        return [
            'is_basic' => self::isBasic($skill),
            'is_cleared' => $cleared,
            'is_available' => ! $cleared && $reason === null,
            'is_current' => ! $this->user->isGuardian() && $this->currentSkill()?->id === $skill->id,
            'locked_reason' => $reason,
            'prerequisite_ids' => $skill->prerequisites->pluck('id')->values(),
            'prerequisite_names' => $skill->prerequisites->pluck('name')->values(),
            'success_score' => (int) ($this->latestBySkill->get($skill->id)?->success_score ?? 0),
        ];
    }

    public function availableAdvanced(): Collection
    {
        return $this->skills->filter(fn ($skill) => ! self::isBasic($skill) && ! $this->isCleared($skill) && $this->lockedReason($skill) === null)
            ->sortBy(fn ($skill) => sprintf('%02d-%05d-%08d', array_search($skill->category, SkillRoadmapCatalog::GENRES, true) === false ? 99 : array_search($skill->category, SkillRoadmapCatalog::GENRES, true), $skill->roadmap_order, $skill->id))->values();
    }
}
