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
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('title', 200)->nullable(); // Title boleh null sesuai desain awal
            $table->text('description')->nullable();
            $table->string('url', 512); // Wajib diisi
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size')->nullable();

            // Relasi ke users
            $table->uuid('uploaded_by')->nullable();
            $table->timestamp('uploaded_at')->nullable();

            // Timestamps & Soft deletes
            $table->timestamps();       // created_at & updated_at
            $table->softDeletes();      // deleted_at

            // Foreign key constraint
            $table->foreign('uploaded_by')
                ->references('uuid')
                ->on('users')
                ->onDelete('set null'); // Jika user dihapus, set kolom ini menjadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
