<?php

namespace App\Support;

use App\Models\Skill;

class SkillRoadmapCatalog
{
    public const GENRES = ['スピン・ターン系', '片足系', 'ジャンプ系', 'タイヤ乗り系', 'サドル操作系', 'ペア・演技系'];

    public static function definitions(): array
    {
        return [
            '180度ターン左右' => [0, 1, []],
            '右スピン3周' => [0, 2, []],
            '左スピン3周' => [0, 3, []],
            '片足走行5m' => [1, 1, []],
            '片足走行10m' => [1, 2, ['片足走行5m']],
            'ホッピング3回' => [2, 1, []],
            'ホッピング5回' => [2, 2, ['ホッピング3回']],
            'ホッピング10回' => [2, 3, ['ホッピング5回']],
            '前進ホッピング3m' => [2, 4, ['ホッピング5回']],
            'ホッピング90度ターン' => [2, 5, ['ホッピング5回']],
            'タイヤ乗り3m' => [3, 1, []],
            'タイヤ乗り5m' => [3, 2, ['タイヤ乗り3m']],
            'タイヤ乗り10m' => [3, 3, ['タイヤ乗り5m']],
            'サドル前出し5m' => [4, 1, []],
            'サドル後ろ出し5m' => [4, 2, []],
            'ペア並走1周' => [5, 1, []],
            '手つなぎ1周' => [5, 2, []],
            '同時アイドリング10回' => [5, 3, []],
            '同時バック走行5m' => [5, 4, []],
            'すれ違いクロス' => [5, 5, []],
            '位置交換クロス' => [5, 6, []],
            '同時ホッピング5回' => [5, 7, ['ホッピング5回']],
            'ペア8の字走行' => [5, 8, []],
            'カノン走行' => [5, 9, []],
            '30秒ペア演技' => [5, 10, [], 2],
        ];
    }

    public static function apply(): void
    {
        $skills = Skill::all()->keyBy('name');
        foreach (self::definitions() as $name => $definition) {
            if (! $skill = $skills->get($name)) continue;
            [$genre, $order, $requires] = $definition;
            $skill->update(['category' => self::GENRES[$genre], 'roadmap_order' => $order, 'required_pair_skills' => $definition[3] ?? 0]);
            $skill->prerequisites()->sync(collect($requires)->map(fn ($name) => $skills->get($name)?->id)->filter()->all());
        }
    }
}
