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
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->enum('email_status', ['Chưa gửi', 'Đã gửi', 'Lỗi gửi'])
                ->default('Chưa gửi')
                ->after('registration_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn('email_status');
        });
    }
};
