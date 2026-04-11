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
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // If farmer is deleted, delete their lands
            $table->string('nickname'); // e.g., "Sawah Kidul"
            $table->decimal('area_size', 8, 2); // Size in Hectares
            $table->decimal('lat', 10, 8)->nullable(); // GPS for mapping
            $table->decimal('lng', 11, 8)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
