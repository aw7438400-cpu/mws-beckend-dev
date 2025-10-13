<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel schedules.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // UUID primary key

            $table->uuid('class_id');   // nanti FK ke classes.id (pastikan classes.id juga UUID)
            $table->uuid('subject_id'); // FK ke subjects.id (UUID)
            $table->uuid('teacher_id'); // FK ke users.uuid
            $table->uuid('updated_by')->nullable(); // FK ke users.uuid

            $table->enum('day_of_week', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location', 150)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('teacher_id')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('uuid')->on('users')->nullOnDelete();

            // Indexes
            $table->index(['class_id', 'day_of_week', 'start_time']);
            $table->index(['teacher_id', 'day_of_week', 'start_time']);
        });

        /**
         * Menambahkan CHECK constraint untuk memastikan start_time < end_time
         * (Hanya akan dijalankan jika database mendukung, contoh: MySQL 8+ atau PostgreSQL)
         */
        DB::statement("ALTER TABLE schedules ADD CONSTRAINT chk_time_order CHECK (start_time < end_time)");
    }

    /**
     * Rollback migrasi
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
