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
        Schema::create('report_signoffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_id');
            $table->enum('role', ['homeroom', 'coordinator', 'principal', 'parent']);
            $table->uuid('signed_by')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('report_id')
                ->references('id')
                ->on('reports')
                ->cascadeOnDelete();

            $table->foreign('signed_by')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();

            // Optional index for faster lookups
            $table->index(['report_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_signoffs');
    }
};
