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
        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_id');
            $table->date('exception_date'); // tanggal pengecualian
            $table->string('reason', 255)->nullable(); // alasan pengecualian

            // Hanya dua status sesuai skema asli
            $table->enum('status', ['cancelled', 'rescheduled']);

            // Jika jadwal di-reschedule
            $table->timestamp('rescheduled_to')->nullable();

            // User yang mengupdate data
            $table->uuid('updated_by')->nullable();

            $table->timestamps();       // created_at & updated_at
            $table->softDeletes();      // deleted_at untuk soft delete

            // Foreign keys
            $table->foreign('schedule_id')
                ->references('uuid')
                ->on('schedules')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('uuid')
                ->on('users')
                ->onDelete('set null');

            // Indexes sesuai rancangan untuk optimasi
            $table->index(['schedule_id', 'exception_date']);
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_exceptions');
    }
};
