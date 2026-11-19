<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('care_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->string('task');
            $table->string('frequency', 100);
            $table->dateTime('next_due_at')->nullable();
            $table->text('notes')->nullable();

            // No timestamps() — schema does not include them
            $table->index('plant_id', 'care_schedules_plant_index');
            $table->index('next_due_at', 'care_schedules_due_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_schedules');
    }
};
