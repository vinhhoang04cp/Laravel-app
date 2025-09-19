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
        Schema::table('congresses', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');              // Loại đại hội
            $table->string('organization_form')->nullable()->after('type'); // Hình thức tổ chức
            $table->string('location')->nullable()->after('organization_form'); // Địa điểm tổ chức
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('congresses', function (Blueprint $table) {
            $table->dropColumn(['type', 'organization_form', 'location']);
        });
    }
};
