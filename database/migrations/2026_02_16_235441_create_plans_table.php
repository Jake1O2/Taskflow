<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price'); // en cents (2900 = $29.00)
            $table->string('stripe_price_id')->nullable();
            $table->json('features')->default(json_encode([]));
            $table->integer('max_projects')->nullable(); // null = unlimited
            $table->integer('max_teams')->nullable(); // null = unlimited
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};