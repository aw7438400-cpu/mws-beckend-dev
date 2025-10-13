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
        Schema::create('habit_trackers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->enum('habit_type', ['sleep', 'reading', 'goal', 'exercise', 'study']);
            $table->integer('target_value');
            $table->integer('achieved_value')->default(0);
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Relasi ke users
            $table->foreign('student_id')->references('uuid')->on('users')->cascadeOnDelete();

            // Index sesuai rancangan
            $table->index(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_trackers');
    }
};
