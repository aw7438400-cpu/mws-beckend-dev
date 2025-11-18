<?php

namespace App\Services;

use App\Models\Teacher;

class TeacherStudentService
{
    public function getStudentsByTeacher($teacherId, $search = null)
    {
        $teacher = Teacher::with(['students' => function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', "%$search%");
            }
        }])->find($teacherId);

        if (!$teacher) {
            return null;
        }

        return [
            'teacher' => $teacher->name,
            'total' => $teacher->students->count(),
            'data' => $teacher->students,
        ];
    }

    public function success($data, $code = 200, $message = 'Success', $meta = [])
    {
        return response()->json([
            'message' => $message,
            'meta' => $meta,
            'data' => $data
        ], $code);
    }

    public function error($message, $code = 400)
    {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
