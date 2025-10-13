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
        Schema::create('history_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_id');
            $table->uuid('editor_id');

            // Enum sesuai rancangan: report_action
            $table->enum('action', ['submit', 'return', 'revise', 'approve', 'send']);

            // Enum sesuai rancangan: report_status
            $table->enum('from_status', ['draft', 'submitted', 'returned', 'revised', 'approved', 'sent'])->nullable();
            $table->enum('to_status', ['draft', 'submitted', 'returned', 'revised', 'approved', 'sent'])->nullable();

            $table->text('note')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('report_id')->references('id')->on('reports')->cascadeOnDelete();
            $table->foreign('editor_id')->references('uuid')->on('users');

            // Indexes
            $table->index(['report_id', 'changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_log');
    }
};
