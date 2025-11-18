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
        $students = $this->service->listStudents($request->all());
        return DetailStudentResource::collection($students);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = $this->service->createStudent($request->validated());
        return new DetailStudentResource($student);
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
