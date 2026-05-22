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
        Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // Mã code (VD: TET2026)
        $table->string('type'); // Loại: 'percent' (phần trăm) hoặc 'fixed' (tiền mặt)
        $table->integer('value'); // Giá trị giảm (VD: 10% hoặc 50.000đ)
        $table->integer('quantity'); // Số lượng mã phát ra
        $table->dateTime('start_date')->nullable(); // Ngày bắt đầu
        $table->dateTime('end_date')->nullable(); // Ngày kết thúc
        $table->boolean('status')->default(1); // 0: Ẩn, 1: Hiện
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
