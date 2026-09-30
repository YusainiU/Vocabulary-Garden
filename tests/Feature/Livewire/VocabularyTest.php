<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Vocabulary\Create;
use App\Livewire\Vocabulary\Index;
use App\Models\User;
use App\Models\VocabularyWord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VocabularyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_plant_a_word(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Create::class)
            ->set('word', 'bonjour')
            ->set('translation', 'hello')
            ->set('language', 'French')
            ->call('save')
            ->assertRedirect(route('vocabulary.index'));

        $this->assertDatabaseHas('vocabulary_words', [
            'user_id' => $user->id,
            'word' => 'bonjour',
            'translation' => 'hello',
        ]);
    }

    public function test_user_cannot_see_another_users_word(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        VocabularyWord::factory()->create(['user_id' => $owner->id, 'word' => 'secretword']);

        Livewire::actingAs($other)->test(Index::class)->assertDontSee('secretword');
    }
}
