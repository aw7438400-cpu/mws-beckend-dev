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
        Schema::create('reflections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('portfolio_item_id');
            $table->uuid('author_id');
            $table->enum('author_role', ['student', 'teacher', 'parent', 'staff']);
            $table->text('content');
            $table->timestamp('reflection_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relations
            $table->foreign('portfolio_item_id')
                ->references('uuid')
                ->on('portfolio_items')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');

            // Optional index (bisa bantu optimasi query berdasarkan tanggal)
            $table->index(['portfolio_item_id', 'reflection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
