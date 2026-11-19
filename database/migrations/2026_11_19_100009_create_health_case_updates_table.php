<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_case_updates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('health_case_id')
                  ->constrained('health_cases')
                  ->onDelete('cascade');
            $table->string('photo_url', 2048)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('health_case_id', 'health_case_updates_case_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_case_updates');
    }
};
