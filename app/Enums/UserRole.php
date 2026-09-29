<?php

namespace App\Enums;

enum UserRole: string
{
    case Member = 'member';
    case Admin = 'admin';
    case Gurdian = 'gurdian';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
