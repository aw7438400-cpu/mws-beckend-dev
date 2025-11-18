<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mentor;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\detail\DetailDashboardResource;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        return response()->json([
            'total_students' => Student::count(),
            'active_mentors' => Teacher::count(),
            'success_rate' => Student::where('progress_status', 'on_track')->count(),
            'recent_activity' => Activity::latest('created_at')->limit(5)->get()
        ]);
    }
}
