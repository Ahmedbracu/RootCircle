<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('listing_id')
                  ->constrained('listings')
                  ->onDelete('cascade');
            $table->foreignId('requester_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->text('message')->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamps();

            $table->unique(['listing_id', 'requester_id'], 'adoption_requests_listing_requester_unique');
            $table->index('requester_id', 'adoption_requests_requester_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
