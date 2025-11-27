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
    Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama PT atau Perorangan
        $table->string('contact_person'); // Nama orang yang dihubungi
        $table->string('phone'); // WA/Telp
        $table->string('category'); // Kategori: 'Ayam', 'Sayur', 'Packaging'
        $table->text('address')->nullable(); 
        $table->boolean('is_active')->default(true); // Masih langganan atau tidak
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
