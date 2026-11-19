<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->text('body');
            $table->timestamps();

            $table->index('user_id', 'comments_user_index');
            $table->index(['post_id', 'created_at'], 'comments_post_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
