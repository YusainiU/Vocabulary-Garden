<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VocabularyAttempt;
use App\Models\VocabularyWord;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo Gardener',
            'email' => 'demo@example.com',
        ]);

        $words = [
            ['bonjour', 'hello', 'bohn-zhoor', 'greeting', 'Bonjour, Marie !', 'A friendly everyday greeting.'],
            ['manger', 'to eat', 'mahn-zhay', 'verb', 'Nous allons manger ensemble.', null],
            ['livre', 'book', 'leevr', 'noun', 'Je lis un livre.', null],
            ['rapide', 'fast', 'rah-peed', 'adjective', 'Le train est rapide.', null],
            ['apprendre', 'to learn', 'ah-prahn-druh', 'verb', 'J’aime apprendre le français.', 'Think: apprendre = acquire knowledge.'],
            ['fleur', 'flower', 'flœr', 'noun', 'Cette fleur est magnifique.', 'Perfect for this app.'],
            ['chemin', 'path / way', 'shuh-man', 'noun', 'Quel est le chemin ?', null],
            ['oublier', 'to forget', 'oo-blee-yay', 'verb', 'Je ne veux pas oublier ce mot.', null],
        ];

        foreach ($words as [$word, $translation, $pronunciation, $pos, $example, $notes]) {
            VocabularyWord::create([
                'user_id' => $user->id,
                'language' => 'French',
                'word' => $word,
                'translation' => $translation,
                'pronunciation' => $pronunciation,
                'part_of_speech' => $pos,
                'example_sentence' => $example,
                'notes' => $notes,
            ]);
        }

        $blooming = VocabularyWord::where('user_id', $user->id)->where('word', 'bonjour')->first();
        for ($i = 0; $i < 7; $i++) {
            VocabularyAttempt::create([
                'vocabulary_word_id' => $blooming->id,
                'user_id' => $user->id,
                'correct' => true,
                'response_time_ms' => 1200 + ($i * 100),
                'created_at' => now()->subDays(1)->subHours($i),
            ]);
        }
        $blooming->update([
            'test_count' => 7, 'correct_count' => 7, 'incorrect_count' => 0,
            'last_tested_at' => now()->subDays(1), 'next_review_at' => now()->addDays(6),
        ]);

        $wilting = VocabularyWord::where('user_id', $user->id)->where('word', 'chemin')->first();
        $wilting->update(['test_count' => 3, 'correct_count' => 2, 'incorrect_count' => 1, 'last_tested_at' => now()->subDays(9), 'next_review_at' => now()->subDays(5)]);

        $dead = VocabularyWord::where('user_id', $user->id)->where('word', 'oublier')->first();
        $dead->update(['test_count' => 2, 'correct_count' => 0, 'incorrect_count' => 2, 'last_tested_at' => now()->subDays(25), 'next_review_at' => now()->subDays(18)]);
    }
}
