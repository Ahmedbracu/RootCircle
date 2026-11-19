<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursery_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nursery_name');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique('user_id', 'nursery_profiles_user_unique');
            $table->index('nursery_name', 'nursery_profiles_name_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursery_profiles');
    }
};
