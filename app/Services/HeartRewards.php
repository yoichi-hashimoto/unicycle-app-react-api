<?php
namespace App\Services;

use App\Models\Point;
use App\Models\User;

class HeartRewards
{
    // Caller holds the recipient's row lock within the same transaction as the heart.
    public static function award(User $recipient): void
    {
        $milestones = intdiv($recipient->received_likes, 100);
        for ($milestone = 1; $milestone <= $milestones; $milestone++) {
            Point::firstOrCreate([
                'source_key' => "heart:{$recipient->id}:".($milestone * 100),
            ], ['user_id' => $recipient->id, 'points' => 5]);
        }
    }
}
