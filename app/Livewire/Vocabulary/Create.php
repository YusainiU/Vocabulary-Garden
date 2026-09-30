<?php

namespace App\Livewire\Vocabulary;

use App\Models\VocabularyWord;
use Livewire\Component;

class Create extends Component
{
    public string $language = 'French';
    public string $word = '';
    public string $translation = '';
    public string $pronunciation = '';
    public string $part_of_speech = '';
    public string $example_sentence = '';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'language' => ['required', 'string', 'max:50'],
            'word' => ['required', 'string', 'max:150'],
            'translation' => ['required', 'string', 'max:255'],
            'pronunciation' => ['nullable', 'string', 'max:255'],
            'part_of_speech' => ['nullable', 'string', 'max:50'],
            'example_sentence' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function save()
    {
        $data = $this->validate();
        $data['user_id'] = auth()->id();
        VocabularyWord::create($data);

        session()->flash('success', "{$this->word} was planted in your garden.");
        return $this->redirect(route('vocabulary.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.vocabulary.create')->layout('layouts.app', ['title' => 'Plant a Word']);
    }
}
