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
        Schema::create('intervention_group_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervention_group_id');
            $table->uuid('student_id');

            $table->foreign('intervention_group_id')
                ->references('id')
                ->on('intervention_groups')
                ->onDelete('cascade');

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_group_students');
    }
};
