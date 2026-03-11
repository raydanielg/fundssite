<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraiser_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('spent_at');
            $table->string('description');
            $table->unsignedBigInteger('amount');
            $table->string('currency', 3)->default('TZS');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraiser_expenses');
    }
};
