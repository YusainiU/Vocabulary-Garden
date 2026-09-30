<?php

namespace App\Enums;

enum WordHealth: string
{
    case SEED = 'seed';
    case GROWING = 'growing';
    case BLOOMING = 'blooming';
    case WILTING = 'wilting';
    case DEAD = 'dead';

    public function label(): string
    {
        return match ($this) {
            self::SEED => 'Seed',
            self::GROWING => 'Growing',
            self::BLOOMING => 'Blooming',
            self::WILTING => 'Wilting',
            self::DEAD => 'Dead',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::SEED => '🌱',
            self::GROWING => '🌿',
            self::BLOOMING => '🌸',
            self::WILTING => '🥀',
            self::DEAD => '💀',
        };
    }
}
