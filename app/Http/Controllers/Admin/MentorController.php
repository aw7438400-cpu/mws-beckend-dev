<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mentor;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MentorController extends Controller
{
    public function index()
    {
        return Mentor::with('user')->get();
    }

    public function assignStudent(Request $request, $id)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);
        $student = Student::findOrFail($request->student_id);
        // pastikan $id adalah teacher id
        $teacher = Teacher::findOrFail($id);
        $student->mentor_id = $teacher->id;
        $student->save();

        Activity::create([
            'date' => now(),
            'activity' => "Student assigned to mentor",
            'student_id' => $student->id,
            'mentor_id' => $teacher->id
        ]);

        return response()->json(['message' => 'Student assigned successfully']);
    }
}
