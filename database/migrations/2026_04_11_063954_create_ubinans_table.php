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
        Schema::create('ubinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained()->cascadeOnDelete();
            $table->decimal('sample_weight_kg', 5, 2); // The 2.5m x 2.5m harvest weight
            $table->decimal('estimated_yield_tons', 8, 2); // The final calculation
            $table->string('weather_note')->nullable(); // e.g., "Kemarau"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubinans');
    }
};
