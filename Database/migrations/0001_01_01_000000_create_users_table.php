<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // primary key
            $table->uuid('uuid')->unique(); // UUID, bukan primary key
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password', 255);

            // Role untuk sistem MTSS
            $table->enum('role', ['admin', 'principal', 'teacher', 'mentor', 'student', 'parent'])
                ->default('student');

            // Supervisor ID: untuk relasi ke user lain (misal teacher ke mentor)
            $table->unsignedBigInteger('supervisor_id')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign key supervisor
            $table->foreign('supervisor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
