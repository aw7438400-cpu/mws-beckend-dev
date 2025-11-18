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
        Schema::create('interventions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->string('group_code')->nullable(); // untuk small-group interventions
            $table->enum('intervention_type', ['tier_2', 'tier_3'])->default('tier_2');
            $table->string('strategy')->nullable();
            $table->enum('progress_status', ['on_track', 'improving', 'needs_attention'])->default('needs_attention');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('teachers')->nullOnDelete(); // who created (mentor)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
