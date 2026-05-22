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
        Schema::table('products', function (Blueprint $table) {
        $table->integer('discount_percent')->nullable()->default(0); // Giảm bao nhiêu %
        $table->dateTime('sale_start')->nullable(); // Bắt đầu giảm từ ngày nào
        $table->dateTime('sale_end')->nullable();   // Kết thúc ngày nào
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['discount_percent', 'sale_start', 'sale_end']);
     });
    }
};
