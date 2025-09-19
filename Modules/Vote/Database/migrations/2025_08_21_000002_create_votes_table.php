<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_session_id')->constrained('vote_sessions')->onDelete('cascade');
            $table->foreignId('shareholder_id')->nullable()->constrained('shareholders')->onDelete('set null');

            $table->enum('choice', ['yes', 'no', 'abstain']);
            $table->bigInteger('shares'); // số cổ phần ứng với lựa chọn
            $table->timestamps();

            $table->unique(['vote_session_id', 'shareholder_id'], 'unique_vote_per_session');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
