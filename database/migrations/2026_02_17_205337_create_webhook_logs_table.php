<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is a late duplicate of the 2024 webhook_logs migration.
        // Keep it idempotent to avoid "table already exists" on existing databases.
        if (Schema::hasTable('webhook_logs')) {
            return;
        }

        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->onDelete('cascade');
            $table->string('event');
            $table->json('payload');
            $table->text('response')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op on rollback to avoid dropping a table created by earlier migration files.
    }
};
