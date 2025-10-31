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
        Schema::create('intervention_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('intervention_id')->index();
            $table->uuid('student_id')->index();
            $table->uuid('assigned_by')->nullable(); // teacher user
            $table->uuid('report_id')->nullable(); // baseline_reports.id
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_assignments');
    }
};
