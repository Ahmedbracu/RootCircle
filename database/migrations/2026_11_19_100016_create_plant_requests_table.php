<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('requester_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('requested_plant_or_seed');
            $table->text('description')->nullable();
            $table->string('status', 50)->default('open');
            $table->timestamps();

            $table->index('requester_id', 'plant_requests_requester_index');
            $table->index('status', 'plant_requests_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_requests');
    }
};
