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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habit_id');
            $table->unsignedInteger('round')->default(1); // 회차정보
            $table->date('start_date');
            $table->date('end_date');
            $table->string('title');
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['creator_id', 'habit_id'], 'logs_user_habit_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
        Schema::dropIfExists('level_logs');
    }
};
