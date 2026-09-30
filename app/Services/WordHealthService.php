<?php

namespace App\Services;

use App\Enums\WordHealth;
use App\Models\VocabularyWord;

class WordHealthService
{
    public function for(VocabularyWord $word): WordHealth
    {
        if (! $word->last_tested_at) {
            return WordHealth::SEED;
        }

        $daysSinceReview = $word->last_tested_at->diffInDays(now());
        $accuracy = $word->accuracy;

        if ($daysSinceReview >= 21) {
            return WordHealth::DEAD;
        }

        if ($daysSinceReview >= 7) {
            return WordHealth::WILTING;
        }

        if ($word->test_count >= 6 && $accuracy >= 85 && $daysSinceReview <= 4) {
            return WordHealth::BLOOMING;
        }

        return WordHealth::GROWING;
    }

    public function reviewInterval(int $streak, bool $correct): int
    {
        if (! $correct) {
            return 1;
        }

        return match (true) {
            $streak <= 1 => 1,
            $streak === 2 => 2,
            $streak === 3 => 4,
            $streak === 4 => 7,
            default => 14,
        };
    }
}
