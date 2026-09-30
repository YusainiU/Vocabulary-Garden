<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vocabulary_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_word_id')->constrained('vocabulary_words')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('correct');
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['user_id', 'created_at']);
            $table->index(['vocabulary_word_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabulary_attempts');
    }
};
