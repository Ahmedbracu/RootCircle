<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sender_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('receiver_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['sender_id', 'receiver_id'], 'messages_sender_receiver_index');
            $table->index(['receiver_id', 'read_at'], 'messages_receiver_read_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
