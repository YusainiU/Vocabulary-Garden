<?php
use App\Livewire\Dashboard;
use App\Livewire\Practice\Session;
use App\Livewire\Vocabulary\Create;
use App\Livewire\Vocabulary\Edit;
use App\Livewire\Vocabulary\Index;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/dashboard');
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/vocabulary', Index::class)->name('vocabulary.index');
    Route::get('/vocabulary/create', Create::class)->name('vocabulary.create');
    Route::get('/vocabulary/{vocabularyWord}/edit', Edit::class)->name('vocabulary.edit');
    Route::get('/practice', Session::class)->name('practice');
});

require __DIR__.'/settings.php';
