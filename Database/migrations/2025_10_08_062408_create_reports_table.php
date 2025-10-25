<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->uuid('id')->primary();

            // classes.primary = string('nisn', 20) -> sesuaikan tipe
            $table->string('class_id', 20);

            // users.pk = uuid (ubah jika users pakai id numeric)
            $table->uuid('student_id');

            $table->enum('report_type', ['monthly', 'mid_semester', 'semester', 'custom']);
            $table->uuid('template_id')->nullable();
            $table->text('narrative')->nullable();
            $table->enum('status', ['draft', 'submitted', 'returned', 'revised', 'approved', 'sent'])
                ->default('draft');
            $table->uuid('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['student_id', 'report_type', 'created_at']);
            $table->index(['status', 'updated_at']);

            // Foreign Keys
            $table->foreign('student_id')
                ->references('uuid')->on('users')
                ->cascadeOnDelete();

            $table->foreign('class_id')
                ->references('nisn')->on('classes')
                ->cascadeOnDelete();

            $table->foreign('template_id')
                ->references('uuid')->on('report_templates')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('uuid')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
