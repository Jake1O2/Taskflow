<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slack_workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('slack_team_id')->unique();
            $table->text('slack_token');
            $table->string('slack_channel_id')->nullable();
            $table->string('channel_name')->nullable();
            $table->boolean('active')->default(true);
            $table->json('events')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slack_workspaces');
    }
};
