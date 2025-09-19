<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            // Số lượng chứng khoán chưa lưu ký
            $table->bigInteger('share_unregistered')->default(0);

            // Số lượng quyền phân bổ chưa lưu ký
            $table->bigInteger('allocation_unregistered')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn(['share_unregistered', 'allocation_unregistered']);
        });
    }
};
