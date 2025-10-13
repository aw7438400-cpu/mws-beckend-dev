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
        Schema::create('class_teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('class_id')
                ->constrained('classes', 'id')
                ->cascadeOnDelete();

            $table->foreignUuid('teacher_id')
                ->constrained('users', 'uuid')
                ->cascadeOnDelete();

            $table->enum('role', ['homeroom', 'subject_teacher', 'assistant'])->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_id', 'teacher_id', 'role'], 'unique_class_teacher_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_teachers');
    }
};
