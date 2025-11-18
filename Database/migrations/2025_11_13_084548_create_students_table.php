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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('photo_id')->nullable();
            $table->string('student_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('student_mws_email')->nullable();
            $table->string('grade')->nullable();
            $table->enum('tier', ['tier_1', 'tier_2', 'tier_3'])->default('tier_1');
            $table->string('type')->nullable();
            $table->string('join_academic_year')->nullable();
            $table->string('class_name')->nullable(); // avoid reserved word
            $table->string('nisn')->nullable();
            $table->string('status')->nullable();
            // mentor -> teachers.id
            $table->foreignId('mentor_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('strategy')->nullable();
            $table->enum('progress_status', ['on_track', 'improving', 'needs_attention'])->default('on_track');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
