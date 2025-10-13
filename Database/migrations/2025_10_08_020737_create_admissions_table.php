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
        Schema::create('admissions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('student_id');
            $table->uuid('schedule_id');

            $table->timestamp('enrolled_at')->nullable();
            $table->enum('status', ['enrolled', 'dropped', 'completed'])->default('enrolled');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('student_id')
                ->references('uuid')   // <-- FK ke users.uuid
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('schedule_id')
                ->references('uuid')   // <-- FK ke schedules.uuid
                ->on('schedules')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
