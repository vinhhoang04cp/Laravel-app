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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Tên hiển thị');
            $table->string('code')->comment('Mã duy nhất dùng gọi template (slug/key)');
            $table->string('subject')->comment('Tiêu đề email (có thể chứa biến)');
            $table->longText('body')->comment('Nội dung Blade');
            $table->json('placeholders')->nullable()->comment('Danh sách biến gợi ý: ["{{name}}","{{otp}}"]');
            $table->boolean('is_html')->default(true);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('code', 'mail_templates_code_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_templates');
    }
};
