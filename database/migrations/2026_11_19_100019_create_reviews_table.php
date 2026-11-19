<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reviewer_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('nursery_id')
                  ->constrained('nursery_profiles')
                  ->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('body')->nullable();
            $table->timestamps();

            $table->unique(['reviewer_id', 'nursery_id'], 'reviews_reviewer_nursery_unique');
            $table->index('nursery_id', 'reviews_nursery_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
