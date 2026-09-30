<?php

namespace App\Livewire\Practice;

use App\Models\VocabularyWord;
use App\Services\PracticeRecorder;
use App\Services\PracticeSelector;
use Livewire\Component;

class Session extends Component
{
    public ?VocabularyWord $word = null;
    public bool $revealed = false;
    public ?int $startedAt = null;
    public int $completed = 0;

    public function mount(): void
    {
        $this->loadNext();
    }

    public function loadNext(): void
    {
        $this->word = app(PracticeSelector::class)->nextFor(auth()->id());
        $this->revealed = false;
        $this->startedAt = now()->getTimestampMs();
    }

    public function reveal(): void
    {
        $this->revealed = true;
    }

    public function answer(bool $correct): void
    {
        if (! $this->word || ! $this->revealed) {
            return;
        }

        $elapsed = now()->getTimestampMs() - ($this->startedAt ?? now()->getTimestampMs());
        app(PracticeRecorder::class)->record($this->word, $correct, $elapsed);
        $this->completed++;
        $this->loadNext();
    }

    public function render()
    {
        return view('livewire.practice.session')->layout('layouts.app', ['title' => 'Practice']);
    }
}
