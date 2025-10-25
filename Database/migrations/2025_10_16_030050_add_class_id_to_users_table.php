<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // samakan tipe dengan classes.nisn
            $table->string('class_id', 20)->nullable()->after('id');

            // tambahkan FK ke classes.nisn
            $table->foreign('class_id')
                ->references('nisn')
                ->on('classes')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop FK lalu kolom
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
