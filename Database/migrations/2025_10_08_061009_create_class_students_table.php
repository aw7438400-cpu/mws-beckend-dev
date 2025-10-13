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
        Schema::create('class_students', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign keys
            $table->uuid('class_id');
            $table->uuid('student_id');

            $table->timestamp('enrolled_at')->nullable();
            $table->enum('status', ['enrolled', 'dropped', 'completed'])
                ->default('enrolled')
                ->comment('Status keikutsertaan siswa di kelas');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_id', 'student_id']);

            // Foreign key constraints
            $table->foreign('class_id')
                ->references('id')      // harus sesuai pk di classes
                ->on('classes')
                ->cascadeOnDelete();

            $table->foreign('student_id')
                ->references('uuid')    // sesuai pk di users
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_students');
    }
};
