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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // No Nota: ORD-20231127-001
        $table->string('customer_name')->nullable(); // Nama pemesan
        
        // Jenis Order (Dine-in, Takeaway, GoFood, GrabFood)
        $table->enum('order_type', ['dine_in', 'takeaway', 'gofood', 'grabfood', 'catering']);
        
        // Status Masakan (Penting untuk layar Dapur)
        $table->enum('status', ['pending', 'cooking', 'ready', 'served', 'canceled'])->default('pending');
        
        // Status Bayar (Penting untuk Kasir/Admin)
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        $table->string('payment_method')->nullable(); // Cash, QRIS, Transfer
        
        $table->decimal('total_price', 12, 2)->default(0); // Total Rupiah
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
