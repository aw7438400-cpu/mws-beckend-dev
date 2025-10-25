<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_teachers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->uuid('id')->primary();

            // classes.nisn adalah string(20) sesuai migration classes
            $table->string('class_id', 20);

            // sesuaikan tipe teacher_id dengan struktur users:
            // - jika users punya kolom uuid -> pakai uuid
            // - jika users pakai id (unsignedBigInteger) -> ubah ke unsignedBigInteger
            $table->uuid('teacher_id'); // <-- ubah ke unsignedBigInteger('teacher_id') jika users.id numeric

            $table->enum('role', ['homeroom', 'subject_teacher', 'assistant'])->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_id', 'teacher_id', 'role'], 'unique_class_teacher_role');

            // foreign keys
            $table->foreign('class_id')
                ->references('nisn')->on('classes')
                ->cascadeOnDelete();

            $table->foreign('teacher_id')
                ->references('uuid')->on('users') // ganti 'uuid' -> 'id' kalau users pakai id numeric
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_teachers');
    }
};
