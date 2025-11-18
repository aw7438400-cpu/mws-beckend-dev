<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData()
    {
        try {
            // 1️⃣ Jumlah siswa aktif (status = Active)
            $activeStudents = Student::where('current_status', 'Active')->count();

            // 2️⃣ Jumlah guru/mentor aktif
            // Pastikan nanti tabel users kamu punya kolom "role" dan "status"
            // Sekarang amanin dulu tanpa filter
            $activeMentors = User::count();

            // 3️⃣ Persentase intervensi mencapai target (sementara 0, karena tabel belum ada)
            $interventionTargetPercentage = 0;

            // 4️⃣ Total semua siswa di program MTSS (semua students)
            $totalStudents = Student::count();

            return [
                'total_active_intervention_students' => $activeStudents, // nanti bisa diganti dengan filter is_intervention
                'total_active_mentors' => $activeMentors,
                'intervention_target_percentage' => $interventionTargetPercentage,
                'total_mtss_students' => $totalStudents,
            ];
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}
