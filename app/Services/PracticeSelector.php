<?php

namespace App\Services;

use App\Models\VocabularyWord;
use Illuminate\Database\Eloquent\Builder;

class PracticeSelector
{
    public function nextFor(int $userId): ?VocabularyWord
    {
        $words = VocabularyWord::query()
            ->where('user_id', $userId)
            ->where(function (Builder $query) {
                $query->whereNull('next_review_at')->orWhere('next_review_at', '<=', now());
            })
            ->get();

        if ($words->isEmpty()) {
            $words = VocabularyWord::query()
                ->where('user_id', $userId)
                ->orderBy('next_review_at')
                ->limit(20)
                ->get();
        }

        return $words
            ->sortByDesc(function (VocabularyWord $word) {
                $daysLate = $word->next_review_at
                    ? max(0, $word->next_review_at->diffInDays(now()))
                    : 30;

                $failureRate = $word->test_count > 0
                    ? ($word->incorrect_count / $word->test_count) * 10
                    : 8;

                $novelty = $word->test_count === 0 ? 12 : 0;

                return ($daysLate * 2) + $failureRate + $novelty + random_int(0, 100) / 100;
            })
            ->first();
    }
}
