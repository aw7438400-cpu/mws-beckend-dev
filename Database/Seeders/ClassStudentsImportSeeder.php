<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassStudentsImportSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/imports/class_students.csv');

        if (!file_exists($path)) {
            dd("File CSV tidak ditemukan di: {$path}");
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file, 0, ',', '"', '"'); // baca header dengan delimiter dan enclosure aman

        while (($data = fgetcsv($file, 0, ',', '"', '"')) !== false) {
            if (count($data) < 10) {
                continue; // skip baris rusak atau kurang kolom
            }

            // pastikan trim semua nilai
            $data = array_map('trim', $data);

            [
                $nisn,
                $photo_id,
                $full_name,
                $nick_name,
                $gender,
                $current_status,
                $student_mws_email,
                $current_grade,
                $class_name,
                $join_academic_year
            ] = array_slice($data, 0, 10);

            if (empty($nisn) || empty($full_name)) {
                continue; // skip baris kosong
            }

            // normalisasi gender
            $gender = ucfirst(strtolower($gender));
            if (!in_array($gender, ['Male', 'Female'])) {
                $gender = null;
            }

            DB::table('classes')->updateOrInsert(
                ['nisn' => $nisn],
                [
                    'photo_id' => $photo_id ?: null,
                    'full_name' => $full_name,
                    'nick_name' => $nick_name ?: null,
                    'gender' => $gender,
                    'current_status' => $current_status ?: null,
                    'student_mws_email' => $student_mws_email ?: null,
                    'current_grade' => $current_grade ?: null,
                    'class_name' => $class_name ?: null,
                    'join_academic_year' => $join_academic_year ?: null,
                    'grade_id' => Str::uuid(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // === 2️⃣ Ambil user berdasarkan email ===
            $student = DB::table('users')->where('email', $student_mws_email)->first();
            if (!$student) {
                continue; // skip kalau user belum ada
            }

            // === 3️⃣ Masukkan ke tabel class_students ===
            DB::table('class_students')->insertOrIgnore([
                'id' => Str::uuid(),
                'class_id' => $nisn,
                'student_id' => $student->uuid,
                'enrolled_at' => $join_academic_year ? substr($join_academic_year, 0, 4) . '-07-01' : null,
                'status' => match (strtolower($current_status)) {
                    'active', 'enrolled' => 'enrolled',
                    'left school', 'dropped' => 'dropped',
                    'completed', 'graduated' => 'completed',
                    default => 'enrolled',
                },
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
