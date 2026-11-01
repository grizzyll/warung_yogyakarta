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
    Schema::table('ingredients', function (Blueprint $table) {
        $table->decimal('current_stock', 12, 3)->default(0)->change();
        $table->decimal('stock_alert', 12, 3)->default(10)->change();
    });
    Schema::table('recipes', function (Blueprint $table) {
        $table->decimal('amount_needed', 12, 3)->change();
    });
}

public function down(): void
{
    Schema::table('ingredients', function (Blueprint $table) {
        $table->integer('current_stock')->default(0)->change();
        $table->integer('stock_alert')->default(10)->change();
    });
    Schema::table('recipes', function (Blueprint $table) {
        $table->integer('amount_needed')->change();
    });
}
}; 