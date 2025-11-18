<?php

namespace App\Services;


use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function listStudents($filters)
    {
        return Student::with('mentor')
            ->when($filters['grade'] ?? null, fn($q, $v) => $q->where('grade', $v))
            ->when($filters['tier'] ?? null, fn($q, $v) => $q->where('tier', $v))
            ->when($filters['type'] ?? null, fn($q, $v) => $q->where('type', $v))
            ->when($filters['mentor_id'] ?? null, fn($q, $v) => $q->where('mentor_id', $v))
            ->get();
    }

    public function createStudent(array $data)
    {
        return Student::create($data);
    }

    public function updateStudent(Student $student, array $data)
    {
        $student->update($data);
        return $student;
    }
}
