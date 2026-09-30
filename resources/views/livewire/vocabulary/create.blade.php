<div class="mx-auto max-w-3xl">
    <div class="mb-8">
        <p class="text-sm font-medium text-emerald-600">New flower</p>
        <flux:heading size="xl">Plant a word</flux:heading>
        <flux:text class="mt-2">Capture the word while it is fresh in your mind.</flux:text>
    </div>
    <form wire:submit="save" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-8">
        @include('livewire.vocabulary.form')
        <div class="mt-8 flex justify-end gap-3">
            <flux:button href="{{ route('vocabulary.index') }}" variant="ghost">Cancel</flux:button>
            <flux:button type="submit" variant="primary" icon="plus">Plant word</flux:button>
        </div>
    </form>
</div>
