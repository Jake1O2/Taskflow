<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            // Renommer user_id en created_by
            $table->renameColumn('user_id', 'created_by');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('priority');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('created_by', 'user_id');
        });
    }
};