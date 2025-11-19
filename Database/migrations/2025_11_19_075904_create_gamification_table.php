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
        Schema::create('gamification', function (Blueprint $table) {
            $table->id();
            $table->morphs('owner'); // owner_type, owner_id (supports student or teacher if needed)
            $table->integer('points')->default(0);
            $table->json('badges')->nullable(); // array of badge slugs or ids
            $table->integer('streak_days')->default(0);
            $table->timestamp('last_checkin_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification');
    }
};
