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
        Schema::create('tier_classifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_id')->unique(); // baseline_reports.id
            $table->string('tier'); // 'tier1','tier2','tier3'
            $table->json('explanation')->nullable(); // reasoning, rules matched
            $table->json('metrics')->nullable(); // scores used
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_classifications');
    }
};
