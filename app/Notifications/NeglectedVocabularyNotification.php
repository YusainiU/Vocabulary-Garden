<?php

namespace App\Notifications;

use App\Models\VocabularyWord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NeglectedVocabularyNotification extends Notification
{
    use Queueable;

    public function __construct(public Collection $words) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->words->count();

        return (new MailMessage)
            ->subject('Your BloomVocab garden needs water')
            ->greeting('Your vocabulary garden misses you!')
            ->line("You have {$count} word(s) that are wilting or dead.")
            ->line($this->words->take(8)->map(fn (VocabularyWord $word) => "{$word->health_emoji} {$word->word} — {$word->translation}")->implode("\n"))
            ->action('Practice now', url('/practice'))
            ->line('A few minutes of recall can bring a neglected word back to life.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'neglected_vocabulary',
            'word_ids' => $this->words->pluck('id')->values()->all(),
            'count' => $this->words->count(),
        ];
    }
}
