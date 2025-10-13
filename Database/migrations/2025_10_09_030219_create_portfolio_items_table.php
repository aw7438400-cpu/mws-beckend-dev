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
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('portfolio_id');
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->enum('item_type', [
                'file',
                'link',
                'text',
                'image',
                'video',
                'audio',
                'slide'
            ])->nullable();
            $table->uuid('resource_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('portfolio_id')
                ->references('uuid')
                ->on('portfolios')
                ->onDelete('cascade');

            $table->foreign('resource_id')
                ->references('uuid')
                ->on('resources')
                ->nullOnDelete();

            $table->index(['portfolio_id', 'created_at'], 'portfolio_items_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
