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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('type', 100);
            $table->json('payload')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('user_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');

            // Indexes (sesuai rancangan database kamu)
            $table->index(['user_id', 'is_read', 'delivered_at']);
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
