<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-medium text-emerald-600">Your garden</p>
            <flux:heading size="xl">Vocabulary</flux:heading>
            <flux:text class="mt-2">Every word has a health, history, and next review.</flux:text>
        </div>
        <flux:button href="{{ route('vocabulary.create') }}" variant="primary" icon="plus">Plant a word</flux:button>
    </div>

    <div class="grid gap-3 md:grid-cols-[1fr_auto]">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search words or translations..." />
        <div class="flex gap-2">
            <flux:select wire:model.live="status" class="min-w-36">
                <flux:select.option value="all">All health</flux:select.option>
                <flux:select.option value="seed">🌱 Seed</flux:select.option>
                <flux:select.option value="growing">🌿 Growing</flux:select.option>
                <flux:select.option value="blooming">🌸 Blooming</flux:select.option>
                <flux:select.option value="wilting">🥀 Wilting</flux:select.option>
                <flux:select.option value="dead">💀 Dead</flux:select.option>
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-950/50">
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-zinc-500">
                        <th class="px-5 py-3">Word</th>
                        <th class="px-5 py-3">Language</th>
                        <th class="px-5 py-3">Translation</th>
                        <th class="px-5 py-3">Health</th>
                        <th class="px-5 py-3">Tests</th>
                        <th class="px-5 py-3">Accuracy</th>
                        <th class="px-5 py-3">Last tested</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($words as $word)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-950/40">
                            <td class="px-5 py-4">
                                <div class="font-semibold">{{ $word->word }}</div>
                                @if ($word->pronunciation)<div class="text-xs text-zinc-500">{{ $word->pronunciation }}</div>@endif
                            </td>
                            <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-300">{{ $word->language }}</td>
                            <td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-300">{{ $word->translation }}</td>
                            <td class="px-5 py-4">
                                <flux:badge>{{ $word->health_emoji }} {{ $word->health_label }}</flux:badge>
                            </td>
                            <td class="px-5 py-4 text-sm">{{ $word->test_count }}</td>
                            <td class="px-5 py-4 text-sm">{{ $word->accuracy }}%</td>
                            <td class="px-5 py-4 text-sm text-zinc-500">
                                {{ $word->last_tested_at?->diffForHumans() ?? 'Never' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <flux:button href="{{ route('vocabulary.edit', $word) }}" wire:navigate size="sm" variant="ghost" icon="pencil" />
                                    <flux:button wire:click="confirmDelete({{ $word->id }})" size="sm" variant="ghost" icon="trash" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-16 text-center"><div class="text-4xl">🌱</div><div class="mt-3 font-semibold">No flowers yet</div><div class="mt-1 text-sm text-zinc-500">Plant your first vocabulary word.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-800">{{ $words->links() }}</div>
    </div>

    <flux:modal wire:model.self="showDeleteModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Remove this word?</flux:heading>
                <flux:text class="mt-2">Its practice history will also be removed. This cannot be undone.</flux:text>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="delete" variant="danger">Remove</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
