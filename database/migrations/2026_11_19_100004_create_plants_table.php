<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('collection_id')
                  ->constrained('plant_collections')
                  ->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('species')->nullable();
            $table->string('photo_url', 2048)->nullable();
            $table->text('description')->nullable();
            $table->string('condition', 100)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->timestamps();

            $table->index('collection_id', 'plants_collection_index');
            $table->index('user_id', 'plants_user_index');
            $table->index('name', 'plants_name_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
