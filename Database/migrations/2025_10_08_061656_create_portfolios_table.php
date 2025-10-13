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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('student_id'); // referensi ke tabel users
            $table->string('title', 200);
            $table->text('description')->nullable();

            // ENUM sesuai rancangan
            $table->enum('visibility', ['private', 'parents', 'school', 'public'])
                ->default('parents');

            $table->timestamps();
            $table->softDeletes();

            // Index untuk optimasi query
            $table->index('student_id');

            // Relasi foreign key
            $table->foreign('student_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
