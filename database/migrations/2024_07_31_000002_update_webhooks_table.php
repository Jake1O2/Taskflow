<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('webhooks')) {
            return;
        }

        $hasStatus = Schema::hasColumn('webhooks', 'status');
        $hasActive = Schema::hasColumn('webhooks', 'active');
        $hasSecret = Schema::hasColumn('webhooks', 'secret');

        if ($hasStatus && ! $hasActive) {
            Schema::table('webhooks', function (Blueprint $table) {
                $table->renameColumn('status', 'active');
            });
            $hasActive = true;
        }

        if (! $hasSecret) {
            Schema::table('webhooks', function (Blueprint $table) {
                $table->string('secret', 32)->after('active')->nullable();
            });
        }

        // Avoid sqlite column alteration limitations in test environments.
        if ($hasActive && DB::getDriverName() !== 'sqlite') {
            Schema::table('webhooks', function (Blueprint $table) {
                $table->boolean('active')->default(false)->change();
            });
        }
    }
};
