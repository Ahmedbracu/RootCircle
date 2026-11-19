<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->text('bio')->nullable()->after('email');
            $table->string('profile_picture')->nullable()->after('bio');
            $table->string('location')->nullable()->after('profile_picture');
            $table->text('gardening_interests')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['bio', 'profile_picture', 'location', 'gardening_interests']);
        });
    }
};
