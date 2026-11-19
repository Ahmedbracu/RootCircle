<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_request_responses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plant_request_id')
                  ->constrained('plant_requests')
                  ->onDelete('cascade');
            $table->foreignId('responder_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('listing_id')
                  ->nullable()
                  ->constrained('listings')
                  ->onDelete('set null');
            $table->text('message')->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamps();

            $table->index('plant_request_id', 'plant_request_responses_request_index');
            $table->index('responder_id', 'plant_request_responses_responder_index');
            $table->index('listing_id', 'plant_request_responses_listing_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_request_responses');
    }
};
