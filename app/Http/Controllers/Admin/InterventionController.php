<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use App\Services\InterventionService;
use App\Http\Requests\Admin\Store\StoreInterventionRequest;
use Illuminate\Support\Facades\Auth;

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
}
