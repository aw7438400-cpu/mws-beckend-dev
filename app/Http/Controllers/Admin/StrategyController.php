<?php

namespace App\Http\Controllers\Admin;

use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class StrategyController extends Controller
{
    /**
     * List strategies with role filtering & pagination
     */
    public function index(Request $request)
    {
        $query = Strategy::query();

        // Only admin & principal see all. Others only see public strategies
        if (!in_array(Auth::user()->role, ['admin', 'principal'])) {
            $query->where('is_public', true);
        }

        $result = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'message' => 'Successfully retrieved strategies',
            'data' => $result,
        ], 200);
    }

    /**
     * Store a new strategy
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'boolean',
        ]);

        $data['created_by'] = Auth::id();

        $strategy = Strategy::create($data);

        return response()->json([
            'message' => 'Strategy created successfully',
            'data' => $strategy,
        ], 201);
    }

    /**
     * Get detail of a strategy
     */
    public function get($id)
    {
        $strategy = Strategy::findOrFail($id);

        return response()->json([
            'message' => 'Strategy detail retrieved',
            'data' => $strategy,
        ], 200);
    }

    /**
     * Update strategy
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'boolean',
        ]);

        $strategy = Strategy::findOrFail($id);
        $strategy->update($data);

        return response()->json([
            'message' => 'Strategy updated successfully',
            'data' => $strategy,
        ], 200);
    }

    /**
     * Delete strategy
     */
    public function destroy($id)
    {
        Strategy::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Strategy deleted successfully',
        ], 200);
    }
}
