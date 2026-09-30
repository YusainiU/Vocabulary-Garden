<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vocabulary_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('language', 50)->default('French')->index();
            $table->string('word', 150);
            $table->string('translation', 255);
            $table->string('pronunciation', 255)->nullable();
            $table->string('part_of_speech', 50)->nullable();
            $table->text('example_sentence')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('test_count')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('incorrect_count')->default(0);
            $table->timestamp('last_tested_at')->nullable()->index();
            $table->timestamp('next_review_at')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'language']);
            $table->index(['user_id', 'next_review_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabulary_words');
    }
};
