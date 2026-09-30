<?php

namespace App\Livewire;

use App\Models\VocabularyWord;
use App\Services\WordStats;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();
        $stats = app(WordStats::class)->forUser($userId);

        $attentionWords = VocabularyWord::query()
            ->where('user_id', $userId)
            ->get()
            ->filter(fn ($word) => in_array($word->health->value, ['wilting', 'dead'], true))
            ->sortByDesc(fn ($word) => $word->health->value === 'dead' ? 2 : 1)
            ->take(8);

        return view('livewire.dashboard', compact('stats', 'attentionWords'))
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
