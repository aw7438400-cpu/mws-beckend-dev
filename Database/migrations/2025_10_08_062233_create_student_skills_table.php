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
        Schema::create('student_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('skill_id');
            $table->string('level', 50)->nullable();
            $table->text('evidence')->nullable();
            $table->date('evaluated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('student_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('skill_id')
                ->references('uuid')
                ->on('skills')
                ->onDelete('cascade');

            // Index sesuai rancangan
            $table->unique(['student_id', 'skill_id', 'evaluated_at'], 'student_skill_eval_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_skills');
    }
};
