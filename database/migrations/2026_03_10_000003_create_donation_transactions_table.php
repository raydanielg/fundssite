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
        Schema::create('donation_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('status')->default('pending');

            $table->unsignedBigInteger('amount');
            $table->string('currency', 3)->default('TZS');

            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();

            $table->text('checkout_url')->nullable();
            $table->text('payment_link_url')->nullable();

            $table->string('external_reference')->nullable();
            $table->string('webhook_event')->nullable();
            $table->string('failure_reason')->nullable();

            $table->json('raw_payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_transactions');
    }
};
