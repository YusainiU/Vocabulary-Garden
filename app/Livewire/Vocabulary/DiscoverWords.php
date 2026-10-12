<?php

namespace App\Livewire\Vocabulary;

use App\Models\VocabularyWord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class DiscoverWords extends Component
{
    public ?array $candidate = null;

    public string $message = '';

    public string $messageType = 'info';

    public string $language = 'french';

    public function giveMeAWord(): void
    {
        $path = app_path("Languages/{$this->language}_vocabulary_1000.json");

        if (! File::exists($path)) {
            $this->notify(
                "The {$this->language} vocabulary file could not be found.",
                'error'
            );

            return;
        }

        try {
            $words = json_decode(
                File::get($path),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $exception) {
            $this->notify(
                "The {$this->language} vocabulary file contains invalid JSON.",
                'error'
            );

            return;
        }

        if (! is_array($words) || count($words) === 0) {
            $this->notify('No vocabulary words were found.', 'error');

            return;
        }

        // Pick a random entry from the JSON list.
        $this->candidate = $words[array_rand($words)];

        $this->message = '';
        $this->messageType = 'info';
    }

    public function addWord(): void
    {
        if (! $this->candidate || ! auth()->check()) {
            $this->notify('Please select a word first.', 'error');

            return;
        }

        $word = trim((string) ($this->candidate['word'] ?? ''));

        if ($word === '') {
            $this->notify('The selected entry has no word.', 'error');

            return;
        }

        $userId = auth()->id();

        // Check whether this user already has this word.
        $alreadyAdded = VocabularyWord::query()
            ->where('user_id', $userId)
            ->whereRaw('LOWER(word) = ?', [mb_strtolower($word)])
            ->exists();

        if ($alreadyAdded) {
            $this->notify(
                "\"{$word}\" has already been added to your vocabulary.",
                'warning'
            );

            return;
        }

        try {
            VocabularyWord::create([
                'user_id' => $userId,
                'language' => $this->candidate['language'] ?? 'French',
                'word' => $word,
                'translation' => $this->candidate['translation'] ?? '',
                'pronunciation' => $this->candidate['pronunciation'] ?? '',
                'part_of_speech' => $this->candidate['part_of_speech'] ?? 'unknown',
                'example_sentence' => $this->candidate['example_sentence'] ?? '',
            ]);
        } catch (QueryException $exception) {
            // A unique database index can prevent duplicates if two requests
            // attempt to add the same word at nearly the same time.
            $nowExists = VocabularyWord::query()
                ->where('user_id', $userId)
                ->whereRaw('LOWER(word) = ?', [mb_strtolower($word)])
                ->exists();

            if ($nowExists) {
                $this->notify(
                    "\"{$word}\" has already been added to your vocabulary.",
                    'warning'
                );

                return;
            }

            report($exception);

            $this->notify(
                'The word could not be added. Please check your database fields.',
                'error'
            );

            return;
        }

        $this->notify("\"{$word}\" has been added to your vocabulary.", 'success');
    }

    private function notify(string $message, string $type = 'info'): void
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.vocabulary.discover-words');
    }
}
