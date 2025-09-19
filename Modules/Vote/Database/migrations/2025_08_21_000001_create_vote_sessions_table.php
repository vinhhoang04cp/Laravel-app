<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vote_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('congress_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('required_percentage', 5, 2)->default(51.00);
            $table->timestamps();

            $table->foreign('congress_id')->references('id')->on('congresses')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('vote_sessions');
    }
};
