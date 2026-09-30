<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\VocabularyWord;
use App\Notifications\NeglectedVocabularyNotification;
use Illuminate\Console\Command;

class NotifyNeglectedVocabulary extends Command
{

    protected $signature = 'vocab:notify-neglected';
    protected $description = 'Notify users about neglected vocabulary words';
    
    public function handle(): int
    {
        User::query()->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $words = VocabularyWord::where('user_id', $user->id)->get()->filter(fn ($word) => in_array($word->health->value, ['wilting', 'dead'], true));
                if ($words->isNotEmpty()) {
                    $user->notify(new NeglectedVocabularyNotification($words));
                }
            }
        });

        $this->info('Neglected vocabulary notifications sent.');
        return self::SUCCESS;
    }
}
