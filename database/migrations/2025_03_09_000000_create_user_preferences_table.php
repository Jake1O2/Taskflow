<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('theme')->default('auto');
            $table->string('primary_color')->default('#2563eb');
            $table->string('accent_color')->default('#059669');
            $table->string('language')->default('fr');
            $table->string('timezone')->default('Europe/Paris');
            $table->boolean('notifications_email')->default(true);
            $table->boolean('notifications_weekly_summary')->default(true);
            $table->boolean('notifications_marketing')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};