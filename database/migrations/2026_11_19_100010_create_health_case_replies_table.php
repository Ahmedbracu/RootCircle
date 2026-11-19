<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_case_replies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('health_case_id')
                  ->constrained('health_cases')
                  ->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('body');
            $table->timestamps();

            $table->index('health_case_id', 'health_case_replies_case_index');
            $table->index('user_id', 'health_case_replies_user_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_case_replies');
    }
};
