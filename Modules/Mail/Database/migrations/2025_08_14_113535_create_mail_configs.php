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
        Schema::create('mail_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Tên cấu hình');
            $table->string('driver')->default('smtp');
            $table->string('host')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->comment('Đã mã hoá bằng Crypt')->nullable();
            $table->string('encryption')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->unsignedInteger('timeout')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('options')->nullable()->comment('Các tuỳ chọn khác: stream, pool, etc.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_configs');
    }
};
