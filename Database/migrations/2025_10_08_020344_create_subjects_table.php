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
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Subject fields
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 150);
            $table->text('description')->nullable();

            // Enum sesuai subject_category di rancangan database
            $table->enum('category', [
                'academic',
                'sel',
                'explore',
                'universal_skill'
            ])->nullable();

            // Default Laravel timestamps
            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
