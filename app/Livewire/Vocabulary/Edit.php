<?php

namespace App\Livewire\Vocabulary;

use App\Models\VocabularyWord;
use Livewire\Component;

class Edit extends Component
{
    public VocabularyWord $vocabularyWord;

    public string $language = '';
    public string $word = '';
    public string $translation = '';
    public string $pronunciation = '';
    public string $part_of_speech = '';
    public string $example_sentence = '';
    public string $notes = '';

    public function mount(VocabularyWord $vocabularyWord): void
    {
        abort_unless($vocabularyWord->user_id === auth()->id(), 403);
        $this->vocabularyWord = $vocabularyWord;
        $this->language = $vocabularyWord->language;
        $this->word = $vocabularyWord->word;
        $this->translation = $vocabularyWord->translation;
        $this->pronunciation = $vocabularyWord->pronunciation ?? '';
        $this->part_of_speech = $vocabularyWord->part_of_speech ?? '';
        $this->example_sentence = $vocabularyWord->example_sentence ?? '';
        $this->notes = $vocabularyWord->notes ?? '';
    }

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
        $this->vocabularyWord->update($this->validate());
        session()->flash('success', 'Your word was updated.');
        return $this->redirect(route('vocabulary.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.vocabulary.edit')->layout('layouts.app', ['title' => 'Edit Word']);
    }
}
