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
        Schema::create('classes', function (Blueprint $table) {
            $table->string('nisn', 20)->primary();
            $table->text('photo_link')->nullable();
            $table->string('full_name');
            $table->string('nick_name')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('current_status')->nullable();
            $table->string('student_mws_email')->nullable();
            $table->string('current_grade')->nullable();
            $table->string('class_name')->nullable();
            $table->string('join_academic_year')->nullable();
            $table->uuid('grade_id');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
