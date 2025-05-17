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
        Schema::create('level_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('log_id');
            $table->unsignedBigInteger('habit_level_id');
            $table->boolean('is_checked')->default(false);
            $table->date('log_date');
            $table->text('content')->nullable();
            $table->text('memo');
            $table->unsignedTinyInteger('seq')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['log_id', 'habit_level_id', 'log_date'], 'level_logs_log_level_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_logs');
    }
};
