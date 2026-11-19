<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['follower_id', 'following_id'], 'follows_pair_unique');
            $table->index('following_id', 'follows_following_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
