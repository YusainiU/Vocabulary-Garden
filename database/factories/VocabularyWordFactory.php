<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VocabularyWord;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<VocabularyWord> */
class VocabularyWordFactory extends Factory
{
    protected $model = VocabularyWord::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'language' => 'French',
            'word' => fake()->unique()->word(),
            'translation' => fake()->word(),
            'pronunciation' => null,
            'part_of_speech' => fake()->randomElement(['noun', 'verb', 'adjective', 'adverb']),
            'example_sentence' => fake()->sentence(),
            'notes' => null,
        ];
    }
}
