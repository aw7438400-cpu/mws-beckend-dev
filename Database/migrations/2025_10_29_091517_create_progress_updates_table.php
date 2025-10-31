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
        Schema::create('progress_updates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id')->index(); // intervention_assignments.id
            $table->uuid('updated_by')->nullable(); // user id
            $table->text('note')->nullable();
            $table->json('metrics')->nullable(); // e.g. {"attendance":90,"score":3}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_updates');
    }
};
