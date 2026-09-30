<div class="mx-auto max-w-2xl">
    <div class="mb-8 text-center">
        <p class="text-sm font-medium text-emerald-600">Water your garden</p>
        <flux:heading size="xl">Practice</flux:heading>
        <flux:text class="mt-2">Completed this session: {{ $completed }}</flux:text>
    </div>

    @if (! $word)
        <div class="rounded-3xl border border-dashed border-emerald-300 bg-emerald-50 p-12 text-center dark:border-emerald-900 dark:bg-emerald-950/20">
            <div class="text-6xl">🌸</div>
            <flux:heading size="lg" class="mt-4">Everything is caught up</flux:heading>
            <flux:text class="mx-auto mt-2 max-w-md">You have no words due right now. Add a few more flowers or come back when they need watering.</flux:text>
            <flux:button class="mt-6" href="{{ route('vocabulary.create') }}" variant="primary">Plant another word</flux:button>
        </div>
    @else
        <div class="rounded-3xl border border-zinc-200 bg-white p-8 text-center shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-12">
            <div class="text-6xl">{{ $word->health_emoji }}</div>
            <p class="mt-5 text-sm text-zinc-500">{{ $word->language }} · {{ $word->part_of_speech ?: 'word' }}</p>
            <div class="mt-3 text-4xl font-semibold tracking-tight">{{ $word->word }}</div>
            @if ($word->pronunciation)<div class="mt-2 text-sm text-zinc-500">/{{ $word->pronunciation }}/</div>@endif

            @if ($revealed)
                <div class="mt-8 rounded-2xl bg-emerald-50 p-6 dark:bg-emerald-950/30">
                    <div class="text-2xl font-semibold text-emerald-800 dark:text-emerald-200">{{ $word->translation }}</div>
                    @if ($word->example_sentence)<p class="mt-3 text-sm italic text-zinc-600 dark:text-zinc-300">“{{ $word->example_sentence }}”</p>@endif
                    @if ($word->notes)<p class="mt-3 text-sm text-zinc-500">{{ $word->notes }}</p>@endif
                </div>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    <flux:button wire:click="answer(false)" variant="danger" icon="x-mark">I forgot</flux:button>
                    <flux:button wire:click="answer(true)" variant="primary" icon="check">I remembered</flux:button>
                </div>
            @else
                <flux:button wire:click="reveal" class="mt-10" variant="primary" icon="eye">Reveal translation</flux:button>
                <p class="mt-4 text-xs text-zinc-500">Think of the translation before revealing it.</p>
            @endif
        </div>

        <div class="mt-5 flex justify-center gap-6 text-sm text-zinc-500">
            <span>{{ $word->test_count }} previous tests</span>
            <span>{{ $word->accuracy }}% accuracy</span>
            <span>Next: {{ $word->next_review_at?->diffForHumans() ?? 'now' }}</span>
        </div>
    @endif
</div>
