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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('requester_id');
            $table->uuid('asset_id')->nullable();
            $table->string('issue_type', 150);
            $table->text('description')->nullable();

            // Enum disesuaikan dengan definisi ticket_priority dan ticket_status
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');

            $table->uuid('assigned_to')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('requester_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('asset_id')
                ->references('id')
                ->on('assets')
                ->onDelete('set null');

            $table->foreign('assigned_to')
                ->references('uuid')
                ->on('users')
                ->onDelete('set null');

            // Index sesuai DBML
            $table->index(['status', 'priority', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
