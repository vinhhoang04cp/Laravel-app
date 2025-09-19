<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('template_code', 190)->nullable();

            $table->unsignedBigInteger('config_id')->nullable(); // mail_config id
            $table->string('to_email', 255);
            $table->string('subject', 255)->nullable();

            $table->boolean('success')->default(false);
            $table->text('error')->nullable();
            $table->string('message_id', 255)->nullable();

            $table->json('payload')->nullable();           // data render
            $table->json('config_snapshot')->nullable();   // snapshot config dùng để gửi
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index(['template_code', 'to_email']);
            $table->index(['config_id', 'success']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('mail_logs');
    }
};
