<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('listing_id')
                  ->constrained('listings')
                  ->onDelete('cascade');
            $table->string('image_url', 2048);

            // No timestamps() — schema does not include them
            $table->index('listing_id', 'listing_images_listing_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_images');
    }
};
