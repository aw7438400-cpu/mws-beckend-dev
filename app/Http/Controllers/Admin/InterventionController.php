<?php

namespace App\Http\Controllers\Admin;

use id;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\InterventionGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\InterventionService;
use App\Http\Requests\Admin\Store\StoreInterventionRequest;

class InterventionController extends Controller
{
    protected $service;

    public function __construct(InterventionService $service)
    {
        $this->service = $service;
    }

    public function store(StoreInterventionRequest $request)
    {
        $intervention = $this->service->createIntervention($request->validated());

        // ambil teacher.id dari user login
        $teacherId = Auth::check() ? optional(Auth::user()->teacher)->id : null;

        // buat activity log tanpa FK error
        $activity = Activity::create([
            'date' => now(),
            'activity' => 'New intervention added',
            'student_id' => $intervention->student_id,
            'mentor_id' => $teacherId, // ← WAJIB pakai teacher.id
        ]);

        return response()->json([
            'message' => 'Intervention created successfully',
            'intervention' => $intervention,
            'activity' => $activity,
        ], 201);
    }

    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string',
            'description' => 'nullable|string',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $group = InterventionGroup::create([
            'group_name' => $validated['group_name'],
            'description' => $validated['description'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Attach students
        $group->students()->sync($validated['student_ids']);

        return response()->json([
            'message' => 'Group intervention created successfully',
            'data' => $group
        ], 201);
    }
}
