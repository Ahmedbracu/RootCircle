<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_cases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plant_id')
                  ->nullable()
                  ->constrained('plants')
                  ->onDelete('set null');
            $table->string('title');
            $table->text('symptoms');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamps();

            $table->index('user_id', 'health_cases_user_index');
            $table->index('plant_id', 'health_cases_plant_index');
            $table->index('status', 'health_cases_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_cases');
    }
};
