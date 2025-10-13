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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('class_id');
            $table->enum('report_type', ['monthly', 'mid_semester', 'semester', 'custom']);
            $table->uuid('template_id')->nullable();
            $table->text('narrative')->nullable();
            $table->enum('status', ['draft', 'submitted', 'returned', 'revised', 'approved', 'sent'])->default('draft');
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('student_id')->references('uuid')->on('users')->cascadeOnDelete();
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreign('template_id')->references('uuid')->on('report_templates')->nullOnDelete();
            $table->foreign('updated_by')->references('uuid')->on('users')->nullOnDelete();

            // Indexes
            $table->index(['student_id', 'report_type', 'created_at']);
            $table->index(['status', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
