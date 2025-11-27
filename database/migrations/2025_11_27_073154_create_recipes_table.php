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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            // Relasi: Menu apa?
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Relasi: Bahannya apa?
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            // Butuh berapa banyak? (Misal: 1 Ayam Bakar butuh 200gram ayam mentah)
            $table->integer('amount_needed'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
