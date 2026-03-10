<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraiser_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('target_amount')->default(150000000);
            $table->unsignedBigInteger('expenses_amount')->default(2289225);
            $table->string('currency', 3)->default('TZS');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraiser_settings');
    }
};
