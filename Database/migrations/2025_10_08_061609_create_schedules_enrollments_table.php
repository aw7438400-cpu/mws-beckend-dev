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
        Schema::create('schedules_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign keys
            $table->uuid('schedule_id');
            $table->uuid('student_id');
            $table->uuid('updated_by')->nullable();

            // Additional columns
            $table->timestamp('enrolled_at')->nullable();

            // Default Laravel timestamps & soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('schedule_id')
                ->references('uuid')
                ->on('schedules')
                ->onDelete('cascade');

            $table->foreign('student_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete(); // jika user pengupdate dihapus, kolom jadi null

            // Indexes
            $table->unique(['schedule_id', 'student_id']);
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules_enrollments');
    }
};
