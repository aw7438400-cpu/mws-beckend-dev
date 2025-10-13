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
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name', 120)->notNullable(); // wajib sesuai DBML
            $table->string('code', 80)->nullable();      // optional
            $table->string('category', 80)->nullable();  // optional
            $table->text('description')->nullable();
            $table->string('icon_url', 255)->nullable();
            $table->timestamps();        // created_at & updated_at
            $table->softDeletes();       // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
