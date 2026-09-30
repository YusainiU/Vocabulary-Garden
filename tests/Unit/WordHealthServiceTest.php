<?php

namespace Tests\Unit;

use App\Enums\WordHealth;
use App\Models\VocabularyWord;
use App\Services\WordHealthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WordHealthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_word_is_a_seed(): void
    {
        $word = VocabularyWord::factory()->create();
        $this->assertSame(WordHealth::SEED, app(WordHealthService::class)->for($word));
    }

    public function test_old_word_is_dead(): void
    {
        $word = VocabularyWord::factory()->create(['last_tested_at' => now()->subDays(22)]);
        $this->assertSame(WordHealth::DEAD, app(WordHealthService::class)->for($word));
    }

    public function test_recent_high_accuracy_word_is_blooming(): void
    {
        $word = VocabularyWord::factory()->create([
            'test_count' => 8,
            'correct_count' => 7,
            'incorrect_count' => 1,
            'last_tested_at' => now()->subDay(),
        ]);
        $this->assertSame(WordHealth::BLOOMING, app(WordHealthService::class)->for($word));
    }
}
