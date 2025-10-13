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
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 200);
            $table->string('category', 100)->nullable();
            $table->string('serial_number', 100)->unique()->nullable();
            $table->string('location', 150)->nullable();

            // Enum status sesuai dengan definisi schema
            $table->enum('status', ['active', 'maintenance', 'retired', 'lost'])->default('active');

            $table->date('purchase_date')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
