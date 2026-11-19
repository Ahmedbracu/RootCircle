<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['sale', 'exchange', 'request', 'adoption']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('status', 50)->default('available');
            $table->timestamps();

            $table->index('user_id', 'listings_user_index');
            $table->index(['type', 'status'], 'listings_type_status_index');
            $table->index('created_at', 'listings_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
