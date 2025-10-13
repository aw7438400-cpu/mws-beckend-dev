<?php

namespace App\Services\Admin;

use App\Models\EmotionalCheckin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmotionalCheckinService
{
    public function searchEmotionalCheckin(array $relations = [], int $paginate = 10, ?string $search = null)
    {
        $query = EmotionalCheckin::with($relations)->orderByDesc('checked_in_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('mood', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        return $query->paginate($paginate);
    }

    /**
     * Create a new Emotional Check-in.
     */
    public function createEmotionalCheckin(array $data)
    {
        DB::beginTransaction();
        try {
            $checkin = EmotionalCheckin::create([
                'user_id' => $data['user_id'],
                'role' => $data['role'],
                'mood' => $data['mood'],
                'intensity' => $data['intensity'],
                'note' => $data['note'] ?? null,
                'checked_in_at' => $data['checked_in_at'],
            ]);

            DB::commit();
            return $checkin;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create emotional checkin: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Find a check-in by UUID or ID.
     */
    public function findByUuidWithRelation(string $id, array $relations = [])
    {
        $checkin = EmotionalCheckin::with($relations)->find($id);

        if (!$checkin) {
            throw new ModelNotFoundException("Emotional Check-in not found.");
        }

        return $checkin;
    }

    /**
     * Update an existing Emotional Check-in.
     */
    public function updateEmotionalCheckin(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $checkin = EmotionalCheckin::findOrFail($id);

            $checkin->update([
                'role' => $data['role'] ?? $checkin->role,
                'mood' => $data['mood'] ?? $checkin->mood,
                'intensity' => $data['intensity'] ?? $checkin->intensity,
                'note' => $data['note'] ?? $checkin->note,
                'checked_in_at' => $data['checked_in_at'] ?? $checkin->checked_in_at,
            ]);

            DB::commit();
            return $checkin;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to update emotional checkin: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Delete a check-in by ID.
     */
    public function destroyByUuid(string $id)
    {
        DB::beginTransaction();
        try {
            $checkin = EmotionalCheckin::findOrFail($id);
            $checkin->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to delete emotional checkin: ' . $th->getMessage());
            throw $th;
        }
    }

    /**
     * Return standardized success response.
     */
    public function success($data, int $code = 200, string $message = 'Success')
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return standardized paginated response.
     */
    public function successPaginate($data, int $code = 200)
    {
        return response()->json($data, $code);
    }
}
