<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('team_members')
            ->whereNull('role')
            ->orWhere('role', '')
            ->orWhere('role', 'member')
            ->update(['role' => 'commenter']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE team_members MODIFY role ENUM('admin','editor','viewer','commenter') NOT NULL DEFAULT 'commenter'"
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE team_members MODIFY role VARCHAR(255) NOT NULL DEFAULT 'member'");
        }
    }
};
