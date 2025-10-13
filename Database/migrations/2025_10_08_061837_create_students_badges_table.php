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
        Schema::create('students_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('badge_id');
            $table->uuid('awarded_by')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->date('awarded_date')->nullable();
            $table->string('evidence_url', 255)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (pastikan users dan badges sudah ada)
            $table->foreign('student_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreign('badge_id')->references('uuid')->on('badges')->onDelete('cascade');
            $table->foreign('awarded_by')->references('uuid')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['student_id', 'badge_id', 'awarded_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_badges');
    }
};
