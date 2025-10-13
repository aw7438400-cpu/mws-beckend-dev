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
        Schema::create('idp_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('staff_id');
            $table->enum('category', [
                'career',
                'physical_health',
                'mental_health',
                'community',
                'spirituality',
            ]);
            $table->string('title', 200);
            $table->decimal('hours', 6, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('activity_date')->nullable();
            $table->string('evidence_url', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Relasi ke tabel users
            $table->foreign('staff_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idp_activities');
    }
};
