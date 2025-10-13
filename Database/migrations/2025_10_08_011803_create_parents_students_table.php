<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parents_students', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            // Foreign keys
            $table->uuid('parent_id');
            $table->uuid('student_id');

            // ENUM untuk relationship sesuai rancangan database
            $table->enum('relationship', ['mother', 'father', 'guardian', 'other'])
                ->default('mother'); // opsional: default bisa diubah atau dihilangkan

            // Boolean permissions
            $table->boolean('can_view_portfolio')->default(true);
            $table->boolean('can_receive_reports')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Index & Foreign Key Constraints
            $table->unique(['parent_id', 'student_id'], 'parents_students_unique');

            $table->foreign('parent_id')
                ->references('uuid')->on('users')
                ->cascadeOnDelete();

            $table->foreign('student_id')
                ->references('uuid')->on('users')
                ->cascadeOnDelete();
        });

        // (Opsional) Tambahkan check constraint di level database
        DB::statement("ALTER TABLE parents_students 
            ADD CONSTRAINT chk_relationship 
            CHECK (relationship IN ('mother', 'father', 'guardian', 'other'))");
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents_students');
    }
};
