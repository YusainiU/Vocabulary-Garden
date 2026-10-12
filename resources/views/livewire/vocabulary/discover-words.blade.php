
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-semibold">
            Discover {{ strtoupper($language) }} Words
        </h1>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Discover a {{ strtoupper($language) }} word, learn its meaning, and add it to your garden.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">
        <x-flux::button
            wire:click="giveMeAWord"
            wire:loading.attr="disabled"
            wire:target="giveMeAWord"
        >
            <span wire:loading.remove wire:target="giveMeAWord">
                Give me a word
            </span>

            <span wire:loading wire:target="giveMeAWord">
                Finding a word...
            </span>
        </x-flux::button>
    </div>

    @if ($message !== '')
        <div
            role="status"
            aria-live="polite"
            @class([
                'rounded-lg border p-4 text-sm',
                'border-green-300 bg-green-50 text-green-800' =>
                    $messageType === 'success',
                'border-amber-300 bg-amber-50 text-amber-800' =>
                    $messageType === 'warning',
                'border-red-300 bg-red-50 text-red-800' =>
                    $messageType === 'error',
                'border-gray-300 bg-gray-50 text-gray-800' =>
                    $messageType === 'info',
            ])
        >
            {{ $message }}
        </div>
    @endif

    @if ($candidate)
        <div class="rounded-xl border border-gray-200 p-6 shadow-sm dark:border-gray-700">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $candidate['language'] ?? 'French' }}
                        @if (!empty($candidate['part_of_speech']))
                            · {{ $candidate['part_of_speech'] }}
                        @endif
                    </p>

                    <h2 class="mt-2 text-3xl font-bold">
                        {{ $candidate['word'] ?? '' }}
                    </h2>

                    @if (!empty($candidate['pronunciation']))
                        <p class="mt-2 text-sm text-gray-500">
                            /{{ $candidate['pronunciation'] }}/
                        </p>
                    @endif
                </div>

                <span aria-hidden="true" class="text-3xl">🌸</span>
            </div>

            <div class="mt-5 space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">
                        Translation
                    </h3>

                    <p class="mt-1">
                        {{ $candidate['translation'] ?? '' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">
                        Example sentence
                    </h3>

                    <p class="mt-1">
                        {{ $candidate['example_sentence'] ?? '' }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <x-flux::button
                    wire:click="addWord"
                    wire:loading.attr="disabled"
                    wire:target="addWord"
                >
                    <span wire:loading.remove wire:target="addWord">
                        Add to my vocabulary
                    </span>

                    <span wire:loading wire:target="addWord">
                        Adding...
                    </span>
                </x-flux::button>

                <x-flux::button
                    wire:click="giveMeAWord"
                    variant="ghost"
                    wire:loading.attr="disabled"
                    wire:target="giveMeAWord"
                >
                    Another word
                </x-flux::button>
            </div>
        </div>
    @endif
</div>
