<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emotional_checkins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // mengacu ke users.id

            $table->enum('role', ['student', 'teacher', 'parent', 'staff']);
            $table->enum('mood', ['very_happy', 'happy', 'neutral', 'sad', 'stressed', 'angry']);
            $table->integer('intensity');
            $table->text('note')->nullable();
            $table->timestamp('checked_in_at');
            $table->timestamps();

            // foreign key ke users.id
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['user_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emotional_checkins');
    }
};
