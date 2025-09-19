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
            $table->dropColumn('ratio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('shareholders', function (Blueprint $table) {
            $table->decimal('ratio', 8, 3)->default(0.000)->nullable(false);
        });
    }
};
