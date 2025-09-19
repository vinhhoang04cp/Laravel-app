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
        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('congress_id')->constrained()->onDelete('cascade');

            $table->string('full_name'); // Họ tên cổ đông
            $table->string('email')->nullable(); // Email (unique trong phạm vi congress)
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('address')->nullable(); // Địa chỉ thường trú
            $table->unsignedBigInteger('shares')->default(0); // Số lượng cổ phần

            $table->string('ownership_registration_number')->nullable(); // Số đăng ký sở hữu
            $table->date('ownership_registration_issue_date')->nullable(); // Ngày cấp số đăng ký sở hữu
            $table->string('nationality')->nullable(); // Quốc tịch

            $table->enum('registration_status', ['Vừa khởi tạo', 'Đã đăng ký', 'Chưa đăng ký', 'Ủy quyền'])
                ->default('Vừa khởi tạo');

            $table->date('transaction_date')->nullable(); // Ngày giao dịch
            $table->string('init_method')->nullable(); // Hình thức khởi tạo (Admin / Import / Tự đăng ký)

            $table->string('confirmation_token')->nullable();
            $table->timestamp('confirmation_expires_at')->nullable();

            $table->string('otp_code')->nullable();
            // 🆕 Thông tin người được ủy quyền
            $table->string('proxy_name')->nullable();  // Họ và tên người được ủy quyền
            $table->string('proxy_phone')->nullable(); // Số điện thoại người được ủy quyền
            $table->string('proxy_id')->nullable();    // CCCD/CMND/Hộ chiếu của người được ủy quyền
            $table->timestamp('otp_expires_at')->nullable();

            $table->boolean('is_confirmed')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // 🔑 Unique constraint cho (congress_id, ownership_registration_number)
            $table->unique(['congress_id', 'ownership_registration_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareholders');
    }
};
