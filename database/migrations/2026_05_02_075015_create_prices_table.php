<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('commodity');          // Jenis komoditas: Padi, Jagung, Kedelai, dll
            $table->string('slug')->unique();     // padi, jagung, kedelai, dsb (untuk kalkulator)
            $table->decimal('price_per_kg', 12, 2);    // Harga per kg (Rp)
            $table->decimal('conversion_factor', 8, 2); // Faktor konversi: kg sample -> ton (contoh: 4.2)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prices');
    }
};