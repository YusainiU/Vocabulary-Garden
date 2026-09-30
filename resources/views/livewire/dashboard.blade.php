<div class="space-y-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Your language garden</p>
            <flux:heading size="xl">Good {{ now()->format('A') }}, {{ auth()->user()->name }}.</flux:heading>
            <flux:text class="mt-2">Every word you practice gets a little more rooted.</flux:text>
        </div>
        <div class="flex gap-2">
            <flux:button href="{{ route('vocabulary.create') }}" variant="primary" icon="plus">Plant a word</flux:button>
            <flux:button href="{{ route('practice') }}" icon="sparkles">Practice</flux:button>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['🌱', 'Words', $stats['total']], ['💧', 'Due today', $stats['due']], ['🥀', 'Wilting', $stats['wilting']], ['💀', 'Dead', $stats['dead']], ['🌸', 'Blooming', $stats['blooming']]] as [$icon, $label, $value])
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="text-2xl">{{ $icon }}</div>
                <div class="mt-3 text-sm text-zinc-500">{{ $label }}</div>
                <div class="mt-1 text-2xl font-semibold">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    @if ($attentionWords->isNotEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-6 dark:border-amber-900 dark:bg-amber-950/30">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="lg">Your garden needs water</flux:heading>
                    <flux:text class="mt-1">These words haven't been recalled recently. A short practice session can bring them back.</flux:text>
                </div>
                <span class="text-3xl">💧</span>
            </div>
            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($attentionWords as $word)
                    <a href="{{ route('vocabulary.edit', $word) }}" wire:navigate class="rounded-xl border border-white/70 bg-white p-4 hover:border-amber-300 dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-lg font-semibold">{{ $word->word }}</span>
                            <span>{{ $word->health_emoji }}</span>
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">{{ $word->translation }}</p>
                        <div class="mt-3 flex items-center justify-between text-xs text-zinc-500">
                            <span>{{ $word->test_count }} tests</span>
                            <span>{{ $word->accuracy }}% accuracy</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-emerald-300 bg-emerald-50/50 p-10 text-center dark:border-emerald-900 dark:bg-emerald-950/20">
            <div class="text-5xl">🌷</div>
            <flux:heading size="lg" class="mt-3">Your garden is healthy</flux:heading>
            <flux:text class="mx-auto mt-2 max-w-lg">Plant more words or practice a few of your existing ones to keep the garden growing.</flux:text>
        </div>
    @endif
</div>
