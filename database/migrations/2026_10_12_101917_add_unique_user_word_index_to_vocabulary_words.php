<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vocabulary_words', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'word'],
                'vocabulary_words_user_word_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('vocabulary_words', function (Blueprint $table) {
            $table->dropUnique('vocabulary_words_user_word_unique');
        });
    }
};
