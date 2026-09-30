<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <flux:input wire:model="word" label="Word" placeholder="bonjour" />
        <flux:input wire:model="translation" label="Translation" placeholder="hello" />
        <flux:input wire:model="language" label="Language" placeholder="French" />
        <flux:input wire:model="pronunciation" label="Pronunciation" placeholder="bohn-zhoor" />
        <flux:input wire:model="part_of_speech" label="Part of speech" placeholder="noun, verb, adjective..." />
        <flux:input wire:model="example_sentence" label="Example sentence" placeholder="Bonjour, comment allez-vous ?" />
    </div>
    <flux:textarea wire:model="notes" label="Notes" rows="4" placeholder="A memory trick, context, related words..." />
</div>
