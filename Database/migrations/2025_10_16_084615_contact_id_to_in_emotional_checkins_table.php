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
        Schema::table('emotional_checkins', function (Blueprint $table) {
            if (Schema::hasColumn('emotional_checkins', 'contact_id')) {
                $table->renameColumn('contact_id', 'contact_id_old');
            }
        });

        Schema::table('emotional_checkins', function (Blueprint $table) {
            // ubah ke TEXT supaya bisa tampung banyak teks (misalnya “Mentor, Counselor, HR”)
            $table->text('contact_id')->nullable()->after('contact_id_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emotional_checkins', function (Blueprint $table) {
            if (Schema::hasColumn('emotional_checkins', 'contact_id')) {
                $table->dropColumn('contact_id');
            }
        });

        Schema::table('emotional_checkins', function (Blueprint $table) {
            if (Schema::hasColumn('emotional_checkins', 'contact_id_old')) {
                $table->renameColumn('contact_id_old', 'contact_id');
            }
        });
    }
};
