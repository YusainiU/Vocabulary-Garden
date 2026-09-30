<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'BloomVocab') }}</title>
    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <header class="border-b border-zinc-200 bg-white/80 backdrop-blur dark:border-zinc-800 dark:bg-zinc-950/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 font-semibold">
                <span class="text-2xl">🌷</span>
                <span>BloomVocab</span>
            </a>
            <nav class="hidden items-center gap-1 md:flex">
                <a href="{{ route('dashboard') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-900">Garden</a>
                <a href="{{ route('vocabulary.index') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-900">Vocabulary</a>
                <a href="{{ route('practice') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-900">Practice</a>
            </nav>
            @auth
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-zinc-500 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit" size="sm" variant="subtle">Log out</flux:button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        {{ $slot }}
    </main>

    @livewireScripts
    @fluxScripts
</body>
</html>
