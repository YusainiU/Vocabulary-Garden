<?php

namespace App\Livewire\Vocabulary;

use App\Models\VocabularyWord;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public string $language = 'all'; //'French';
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected $queryString = ['search', 'status', 'language'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        VocabularyWord::where('user_id', auth()->id())->findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'Word removed from your garden.');
    }

    public function render()
    {
        $allWords = VocabularyWord::query()
            ->where('user_id', auth()->id())
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('word', 'like', "%{$this->search}%")
                    ->orWhere('translation', 'like', "%{$this->search}%");
            }))
            ->when($this->language !== 'all', fn ($query) => $query->where('language', $this->language))
            ->latest()
            ->get();

        if ($this->status !== 'all') {
            $allWords = $allWords->filter(fn ($word) => $word->health->value === $this->status)->values();
        }

        $perPage = 12;
        $currentPage = $this->getPage();
        $items = $allWords->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $words = new LengthAwarePaginator($items, $allWords->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('livewire.vocabulary.index', compact('words'))
            ->layout('layouts.app', ['title' => 'My Vocabulary']);
    }
}
