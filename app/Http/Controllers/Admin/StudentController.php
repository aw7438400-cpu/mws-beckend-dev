<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Store\StoreStudentRequest;
use App\Http\Resources\Admin\Detail\DetailStudentResource;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Student::query();

        // Filter tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->input('tier'));
        }

        // Filter class_name
        if ($request->filled('class_name')) {
            $query->where('class_name', $request->input('class_name'));
        }

        // Filter mentor_id
        if ($request->filled('mentor_id')) {
            $query->where('mentor_id', $request->input('mentor_id'));
        }

        // Search student_name / nisn
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('student_name', 'like', "%{$s}%")
                    ->orWhere('nisn', 'like', "%{$s}%");
            });
        }

        // Pagination
        $perPage = intval($request->get('per_page', 10));

        // Include relation
        $students = $query->with('mentor')->paginate($perPage);

        return DetailStudentResource::collection($students);
    }

    public function show(Student $student)
    {
        return new DetailStudentResource($student->load('mentor'));
    }

    public function update(StoreStudentRequest $request, Student $student)
    {
        $student = $this->service->updateStudent($student, $request->validated());
        return new DetailStudentResource($student);
    }
}
