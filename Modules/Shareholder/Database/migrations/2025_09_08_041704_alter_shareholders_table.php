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
            // Lưu ký trong số lượng chứng khoán
            $table->bigInteger('share_deposited')->default(0)->after('share_unregistered');

            // Tổng cộng số lượng chứng khoán
            $table->bigInteger('share_total')->default(0)->after('share_deposited');

            // Lưu ký trong số lượng quyền phân bổ
            $table->bigInteger('allocation_deposited')->default(0)->after('allocation_unregistered');

            // Tổng cộng số lượng quyền phân bổ
            $table->bigInteger('allocation_total')->default(0)->after('allocation_deposited');

            // Mã nhà đầu tư (Investor code)
            $table->string('investor_code')->nullable();

            // Lưu trực tiếp Tỷ lệ % (ví dụ: 12.345 = 12.345%)
            $table->decimal('ratio', 8, 3)->default(0)->after('investor_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn(['share_deposited', 'allocation_deposited', 'share_total', 'allocation_total', 'investor_code', 'ratio']);
        });
    }
};
