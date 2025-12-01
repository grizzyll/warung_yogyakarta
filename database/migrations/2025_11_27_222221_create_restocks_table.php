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
        Schema::create('restocks', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->nullable(); // Nomor Nota dari Supplier
        $table->foreignId('supplier_id')->constrained(); // Beli di siapa?
        $table->decimal('total_spent', 12, 2); // Total belanja berapa rupiah
        $table->date('purchase_date'); // Tanggal beli
        $table->foreignId('user_id')->constrained(); // Siapa admin yang input?
        $table->timestamps();
    });

    Schema::create('restock_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('restock_id')->constrained()->onDelete('cascade');
        $table->foreignId('ingredient_id')->constrained(); // Beli bahan apa
        $table->integer('quantity'); // Berapa banyak (misal 50)
        $table->decimal('price_per_unit', 10, 2); // Harga satuan saat beli
        $table->decimal('subtotal', 12, 2); // Total harga item ini
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restocks');
    }
};
