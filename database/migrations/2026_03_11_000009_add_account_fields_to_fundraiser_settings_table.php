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
        Schema::table('fundraiser_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('fundraiser_settings', 'selcom_name')) {
                $table->string('selcom_name')->nullable();
            }
            if (!Schema::hasColumn('fundraiser_settings', 'selcom_number')) {
                $table->string('selcom_number')->nullable();
            }
            if (!Schema::hasColumn('fundraiser_settings', 'tigo_name')) {
                $table->string('tigo_name')->nullable();
            }
            if (!Schema::hasColumn('fundraiser_settings', 'tigo_number')) {
                $table->string('tigo_number')->nullable();
            }
            if (!Schema::hasColumn('fundraiser_settings', 'crdb_name')) {
                $table->string('crdb_name')->nullable();
            }
            if (!Schema::hasColumn('fundraiser_settings', 'crdb_number')) {
                $table->string('crdb_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fundraiser_settings', function (Blueprint $table) {
            $table->dropColumn([
                'selcom_name',
                'selcom_number',
                'tigo_name',
                'tigo_number',
                'crdb_name',
                'crdb_number'
            ]);
        });
    }
};
