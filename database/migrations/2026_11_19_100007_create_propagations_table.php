<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propagations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->enum('method', ['seed', 'cutting', 'division']);
            $table->date('start_date');
            $table->text('root_development')->nullable();
            $table->string('status', 50)->default('in_progress');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('plant_id', 'propagations_plant_index');
            $table->index('status', 'propagations_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propagations');
    }
};
