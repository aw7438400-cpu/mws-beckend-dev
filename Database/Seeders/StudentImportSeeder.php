<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentImportSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/imports/Database Student MWS (Keep Update) - Complete Database (2).csv');

        if (!file_exists($path)) {
            $this->command->error("❌ File tidak ditemukan di: $path");
            return;
        }

        $file = fopen($path, 'r');
        fgetcsv($file); // skip header

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            $row = array_map('trim', $row);

            Student::create([
                'nisn'               => $row[15] ?? null,
                'photo_id'           => $row[1] ?? null,
                'student_name'       => $row[2] ?? null,   // full name → student_name
                'gender'             => $row[4] ?? null,
                'student_mws_email'  => $row[6] ?? null,
                'grade'              => $row[7] ?? null,
                'class_name'         => $row[8] ?? null,   // class_name → class
                'join_academic_year' => $row[9] ?? null,
                'status'             => $row[5] ?? null,   // current_status → status
                'tier'               => 'tier_1',          // DEFAULT REQUIRED
                'type'               => null,
                'mentor_id'          => null,
                'strategy'           => null,
                'progress_status'    => 'on_track',
            ]);

            $count++;
        }

        fclose($file);

        $this->command->info("✅ Berhasil import $count siswa.");
    }
}
