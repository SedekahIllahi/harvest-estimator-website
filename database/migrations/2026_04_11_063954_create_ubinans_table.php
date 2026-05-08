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
                // Link it to the land. If a land is deleted, its harvest history goes with it.
                $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
                
                // The inputs & outputs
                $table->decimal('sample_weight_kg', 5, 2); 
                $table->decimal('estimated_yield_kg', 10, 2); 
                
                // Status tracking
                $table->enum('status', ['pending', 'harvested', 'failed'])->default('pending');
                $table->date('projected_harvest_date');
                
                $table->text('notes')->nullable();
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
