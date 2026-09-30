<?php

namespace App\Services;

use App\Models\VocabularyWord;

class WordStats
{
    public function forUser(int $userId): array
    {
        $words = VocabularyWord::where('user_id', $userId)->get();

        return [
            'total' => $words->count(),
            'due' => $words->filter->isDue()->count(),
            'wilting' => $words->filter(fn ($word) => $word->health->value === 'wilting')->count(),
            'dead' => $words->filter(fn ($word) => $word->health->value === 'dead')->count(),
            'blooming' => $words->filter(fn ($word) => $word->health->value === 'blooming')->count(),
        ];
    }
}
