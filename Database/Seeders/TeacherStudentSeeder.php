<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Student;
use Exception;

class TeacherStudentSeeder extends Seeder
{
    public function run(): void
    {
        // Wrap everything in a transaction so we can rollback on error
        DB::beginTransaction();

        try {
            // Matikan foreign key checks sementara untuk menghindari constraint error
            // (Gunakan ini dengan hati-hati di environment non-production atau jika kamu yakin)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // ==========================
            // 1. Buat teacher (safely)
            // ==========================
            $emails = [
                ['name' => 'Ms. Latifah', 'email' => 'latifah@millennia21.id'],
                ['name' => 'Ms. Kholida', 'email' => 'kholida@millennia21.id'],
                ['name' => 'Mr. Aria', 'email' => 'aria@millennia21.id'],
                ['name' => 'Ms. Hana', 'email' => 'hana.fajria@millennia21.id'],
                ['name' => 'Ms. Wina', 'email' => 'wina@millennia21.id'],
                ['name' => 'Ms. Sarah', 'email' => 'sarahyuliana@millennia21.id'],
                ['name' => 'Ms. Hanny', 'email' => 'hanny@millennia21.id'],
                ['name' => 'Pak Dodi', 'email' => 'dodi@millennia21.id'],
                ['name' => 'Pak Faisal', 'email' => 'faisal@millennia21.id'],
                ['email' => 'abu@millennia21.id', 'name' => 'Abu Bakar Ali, S.Sos I'],
                ['email' => 'afiyanti.hardiansari@millennia21.id', 'name' => 'Afiyanti Hardiansari'],
                ['email' => 'alinsuwisto@millennia21.id', 'name' => 'Auliya Hasanatin Suwisto, S.IKom'],
                ['email' => 'aprimaputri@millennia21.id', 'name' => 'Ayunda Primaputri'],
                ['email' => 'belakartika@millennia21.id', 'name' => 'Bela Kartika Sari'],
                ['email' => 'nana@millennia21.id', 'name' => 'Berliana Gustina Siregar'],
                ['email' => 'devi.agriani@millennia21.id', 'name' => 'Devi Agriani, S.Pd.'],
                ['email' => 'diya@millennia21.id', 'name' => 'Diya Pratiwi, S.S'],
                ['email' => 'fransiskaeva@millennia21.id', 'name' => 'Fransiska Evasari, S.Pd'],
                ['email' => 'gundah@millennia21.id', 'name' => 'Gundah Basiswi, S.Pd'],
                ['email' => 'hadi@millennia21.id', 'name' => 'Hadi'],
                ['email' => 'himawan@millennia21.id', 'name' => 'Himawan Rizky Syaputra'],
                ['email' => 'alys@millennia21.id', 'name' => 'Krisalyssa Esna Rehulina Tarigan, S.K.Pm'],
                ['email' => 'maria@millennia21.id', 'name' => 'Maria Rosa Apriliana Jaftoran'],
                ['email' => 'nadiamws@millennia21.id', 'name' => 'Nadia'],
                ['email' => 'nanda@millennia21.id', 'name' => 'Nanda Citra Ryani, S.IP'],
                ['email' => 'nathasya@millennia21.id', 'name' => 'Nathasya Christine Prabowo, S.Si'],
                ['email' => 'novia@millennia21.id', 'name' => 'Novia Syifaputri Ramadhan'],
                ['email' => 'widya@millennia21.id', 'name' => 'Nurul Widyaningtyas Agustin'],
                ['email' => 'pipiet@millennia21.id', 'name' => 'Pipiet Anggreiny, S.TP'],
                ['email' => 'cecil@millennia21.id', 'name' => 'Pricilla Cecil Leander, S.Pd'],
                ['email' => 'putri.fitriyani@millennia21.id', 'name' => 'Putri Fitriyani, S.Pd'],
                ['email' => 'raisa@millennia21.id', 'name' => 'Raisa Ramadhani'],
                ['email' => 'rifqi.satria@millennia21.id', 'name' => 'Rifqi Satria Permana, S.Pd'],
                ['email' => 'risma.angelita@millennia21.id', 'name' => 'Risma Ayu Angelita'],
                ['email' => 'risma.galuh@millennia21.id', 'name' => 'Risma Galuh Pitaloka Fahdin'],
                ['email' => 'rizkinurul@millennia21.id', 'name' => 'Rizki Nurul Hayati'],
                ['email' => 'robby.noer@millennia21.id', 'name' => 'Robby Noer Abjuny'],
                ['email' => 'triayulestari@millennia21.id', 'name' => 'Tri Ayu Lestari'],
                ['email' => 'triafadilla@millennia21.id', 'name' => 'Tria Fadilla'],
                ['email' => 'vickiaprinando@millennia21.id', 'name' => 'Vicki Aprinando'],
                ['email' => 'yohana@millennia21.id', 'name' => 'Yohana Setia Risli'],
                ['email' => 'yosafat@millennia21.id', 'name' => 'Yosafat Imanuel Parlindungan'],
                ['email' => 'oudy@millennia21.id', 'name' => 'Zavier Cloudya Mashareen'],
                ['email' => 'zolla@millennia21.id', 'name' => 'Zolla Firmalia Rossa'],
                ['email' => 'chaca@millennia21.id', 'name' => 'Chantika Nur Febryanti'],
                ['email' => 'sisil@millennia21.id', 'name' => 'Najmi Silmi Mafaza'],
                ['email' => 'nayandra@millennia21.id', 'name' => 'Nayandra Hasan Sudra'],
            ];

            $createdTeachers = 0;
            foreach ($emails as $item) {
                // Validasi bentuk array dan field yang diperlukan
                if (!is_array($item)) continue;
                if (empty($item['email'])) continue;

                $email = $item['email'];
                $name = $item['name'] ?? null;

                // Jika name kosong buat fallback dari email, pastikan $email adalah string
                if (!$name && is_string($email)) {
                    $username = explode('@', $email)[0] ?? $email;
                    $name = ucfirst(str_replace(['.', '_'], ' ', $username));
                }

                // Pastikan $email bertipe string
                if (!is_string($email)) continue;

                // Gunakan updateOrCreate agar apabila email sudah ada kita update (idempoten)
                $teacher = Teacher::updateOrCreate(
                    ['email' => $email],
                    ['name' => $name]
                );

                if ($teacher->wasRecentlyCreated || $teacher->exists) {
                    $createdTeachers++;
                }
            }

            // Ambil semua teacher ids untuk assignment siswa
            $teacherIds = Teacher::pluck('id')->toArray();

            if (empty($teacherIds)) {
                // rollback dan beri pesan error jika tetap tidak ada teacher
                DB::rollBack();
                $this->command->error('❌ No teachers found after seeding teachers. Aborting.');
                return;
            }

            // ==========================
            // 2. Data siswa dummy
            // ==========================
            $studentNames = [
                'Wira Kusuma Ramli',
                'Mafesia Fihir',
                'Afzal Isfandiyar',
                'Kaio Pranadipa',
                'Narinka Keshwari Arunika',
                'Narendratama Prabandaru Mansursyah',
                'Makara Aiyana Kumbara',
                'Gallendra Abqory Suseno',
                'Kyro Anderson Wakita',
                'Rex Syailendra Pongki',
            ];

            $createdStudents = 0;
            $i = 0;
            foreach ($studentNames as $sName) {
                // Simple validation
                if (!is_string($sName) || trim($sName) === '') continue;

                // Assign mentor safely (rotasi)
                $mentorId = $teacherIds[$i % count($teacherIds)];

                // Gunakan updateOrCreate apabila ada unique constraint (mis. student_name + class_name)
                $student = Student::updateOrCreate(
                    [
                        'student_name' => $sName,
                        'class_name' => '5A',
                    ],
                    [
                        'status' => (rand(0, 1) ? 'active' : 'intervention'),
                        'mentor_id' => $mentorId,
                        'tier' => 'tier_1',
                        'progress_status' => 'on_track',
                    ]
                );

                if ($student->wasRecentlyCreated || $student->exists) {
                    $createdStudents++;
                }

                $i++;
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Commit transaction
            DB::commit();

            // Info output: berapa banyak masuk
            $this->command->info("✅ Dummy Teachers & Students created/updated successfully.");
            $this->command->info("   Teachers processed: {$createdTeachers}");
            $this->command->info("   Students processed: {$createdStudents}");
        } catch (Exception $e) {
            // Pastikan rollback dan re-enable FK checks kalau error
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Log error ke console supaya kamu tahu apa yang salah
            $this->command->error('❌ Seeder failed: ' . $e->getMessage());
            // Jika mau, tampilkan stack trace singkat (opsional)
            // $this->command->error($e->getTraceAsString());
        }
    }
}
