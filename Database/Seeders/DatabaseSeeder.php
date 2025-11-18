<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder.
     */
    public function run(): void
    {
        $this->call([
            BulkEmotionalCheckinsSeeder::class,
            GradeSeeder::class,
            ClassStudentsImportSeeder::class,
            PermissionUserSeeder::class,
            UserSeeder::class,
            StudentImportSeeder::class,
            TeacherStudentSeeder::class,
        ]);
    }
}
