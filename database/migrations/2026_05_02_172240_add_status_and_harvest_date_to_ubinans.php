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
        Schema::table('ubinans', function (Blueprint $table) {
            // Tambahkan kolom status dengan nilai default 'pending'
            $table->enum('status', ['pending', 'harvested', 'failed'])->default('pending')->after('estimated_yield_tons');
            // Tambahkan kolom tanggal perkiraan panen, boleh null
            $table->date('projected_harvest_date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ubinans', function (Blueprint $table) {
            $table->dropColumn(['status', 'projected_harvest_date']);
        });
    }
};