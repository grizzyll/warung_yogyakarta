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
    Schema::create('dining_tables', function (Blueprint $table) {
        $table->id();
        $table->string('number')->unique();                 // "1", "2", "L3"
        $table->uuid('token')->unique();                    // dipakai di URL QR: /m/{token}
        $table->string('status')->default('available');     // available|occupied|waiting_payment|needs_cleaning
        $table->unsignedSmallInteger('capacity')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('dining_tables');
}
};