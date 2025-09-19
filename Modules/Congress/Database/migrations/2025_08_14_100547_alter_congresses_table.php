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
        Schema::table('congresses', function (Blueprint $table) {
            // Đổi từ date sang timestamp
            $table->dateTime('scheduled_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('congresses', function (Blueprint $table) {
            // Quay lại date nếu rollback
            $table->date('scheduled_at')->change();
        });
    }
};
