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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();

            // Mengikuti Enum report_action di rancangan kamu
            $table->enum('action', [
                'submit',
                'return',
                'revise',
                'approve',
                'send',
            ])->nullable();

            $table->string('target_type', 100)->nullable();
            $table->uuid('target_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            // Relasi dengan tabel users
            $table->foreign('user_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('set null');

            // Index tambahan untuk optimasi query log
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
