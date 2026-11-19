<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_growth_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->string('photo_url', 2048)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('logged_at')->useCurrent();

            // No timestamps() — schema uses only logged_at
            $table->index(['plant_id', 'logged_at'], 'plant_growth_logs_plant_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_growth_logs');
    }
};
