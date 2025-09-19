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
            // Mã định dạnh NĐT (SID)
            $table->string('sid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn('sid');
        });
    }
};
