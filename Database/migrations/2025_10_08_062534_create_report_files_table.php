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
        Schema::create('report_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // FK ke reports
            $table->foreignUuid('report_id')->constrained('reports')->cascadeOnDelete();

            $table->string('file_name', 255);
            $table->string('file_url', 512);
            $table->string('file_type', 50)->nullable();

            // FK ke users.uuid, bukan id
            $table->uuid('uploaded_by');
            $table->uuid('updated_by')->nullable();

            $table->timestamp('uploaded_at');
            $table->timestamps();
            $table->softDeletes();

            // Define foreign keys dengan kolom spesifik
            $table->foreign('uploaded_by')->references('uuid')->on('users');
            $table->foreign('updated_by')->references('uuid')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_files');
    }
};
