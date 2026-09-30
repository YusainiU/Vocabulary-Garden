<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VocabularyAttempt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vocabulary_word_id', 'user_id', 'correct', 'response_time_ms', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'correct' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(VocabularyWord::class, 'vocabulary_word_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
