<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reporter_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            // Polymorphic: reportable_type + reportable_id
            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');
            $table->text('reason');
            $table->string('status', 50)->default('pending');
            $table->foreignId('resolved_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['reportable_type', 'reportable_id'], 'reports_reportable_index');
            $table->index('reporter_id', 'reports_reporter_index');
            $table->index('status', 'reports_status_index');
            $table->index('resolved_by', 'reports_resolved_by_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
