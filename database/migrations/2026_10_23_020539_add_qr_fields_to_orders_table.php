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
    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('dining_table_id')->nullable()->after('table_number')
              ->constrained('dining_tables')->nullOnDelete();
        $table->date('queue_date')->nullable()->after('dining_table_id');
        $table->unsignedSmallInteger('queue_number')->nullable()->after('queue_date');
        $table->string('source')->default('cashier')->after('queue_number'); // cashier|qr|online

        $table->unique(['queue_date', 'queue_number']);  // antrian tidak boleh kembar per hari
        $table->index('source');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropUnique(['queue_date', 'queue_number']);
        $table->dropIndex(['source']);
        $table->dropConstrainedForeignId('dining_table_id');
        $table->dropColumn(['queue_date', 'queue_number', 'source']);
    });
}
}; 