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
         Schema::table('restocks', function (Blueprint $table) {
        // Status: approved (langsung masuk), pending (nunggu owner), rejected (ditolak)
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('total_spent');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('restocks', function (Blueprint $table) {
        $table->dropColumn('status');
    });
    }
};
