<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('commodity_prices', function (Blueprint $table) {
            $table->id();
            $table->string('crop_name'); // e.g., 'Padi', 'Jagung'
            $table->decimal('price_per_kg', 10, 2);
            $table->timestamp('effective_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_prices');
    }
};
