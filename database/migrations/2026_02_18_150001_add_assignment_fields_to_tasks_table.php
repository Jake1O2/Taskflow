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
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'assigned_to')) {
                $table->foreignId('assigned_to')
                    ->nullable()
                    ->after('project_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('tasks', 'assigned_at')) {
                $table->dateTime('assigned_at')->nullable()->after('assigned_to');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }

            if (Schema::hasColumn('tasks', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
        });
    }
};
