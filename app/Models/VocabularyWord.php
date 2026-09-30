<?php

namespace App\Models;

use App\Enums\WordHealth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class VocabularyWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'language', 'word', 'translation', 'pronunciation',
        'part_of_speech', 'example_sentence', 'notes', 'test_count',
        'correct_count', 'incorrect_count', 'last_tested_at', 'next_review_at',
    ];

    protected function casts(): array
    {
        return [
            'last_tested_at' => 'datetime',
            'next_review_at' => 'datetime',
        ];
    }

    protected $appends = ['health', 'health_label', 'health_emoji', 'accuracy'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(VocabularyAttempt::class);
    }

    public function getHealthAttribute(): WordHealth
    {
        return app(\App\Services\WordHealthService::class)->for($this);
    }

    public function getHealthLabelAttribute(): string
    {
        return $this->health->label();
    }

    public function getHealthEmojiAttribute(): string
    {
        return $this->health->emoji();
    }

    public function getAccuracyAttribute(): int
    {
        return $this->test_count > 0
            ? (int) round(($this->correct_count / $this->test_count) * 100)
            : 0;
    }

    public function isDue(): bool
    {
        return ! $this->next_review_at || $this->next_review_at->isPast();
    }
}
