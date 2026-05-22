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
        Schema::table('users', function (Blueprint $table) {
        // Thêm cột avatar, cho phép rỗng (nullable), đặt sau cột password cho đẹp
        $table->string('avatar')->nullable()->after('password');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Nếu rollback (quay lui) thì xóa cột này đi
        $table->dropColumn('avatar');
    });
    }
};
