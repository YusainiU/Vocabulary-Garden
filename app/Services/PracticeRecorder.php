<?php

namespace App\Services;

use App\Models\VocabularyAttempt;
use App\Models\VocabularyWord;
use Illuminate\Support\Facades\DB;

class PracticeRecorder
{
    public function record(VocabularyWord $word, bool $correct, ?int $responseTimeMs = null): void
    {
        DB::transaction(function () use ($word, $correct, $responseTimeMs) {
            $streak = $word->attempts()->latest('created_at')->take(5)->get()
                ->takeWhile(fn ($attempt) => $attempt->correct)
                ->count();

            VocabularyAttempt::create([
                'vocabulary_word_id' => $word->id,
                'user_id' => $word->user_id,
                'correct' => $correct,
                'response_time_ms' => $responseTimeMs,
                'created_at' => now(),
            ]);

            $interval = app(WordHealthService::class)->reviewInterval($streak + ($correct ? 1 : 0), $correct);

            $word->update([
                'test_count' => $word->test_count + 1,
                'correct_count' => $word->correct_count + ($correct ? 1 : 0),
                'incorrect_count' => $word->incorrect_count + ($correct ? 0 : 1),
                'last_tested_at' => now(),
                'next_review_at' => now()->addDays($interval),
            ]);
        });
    }
}
