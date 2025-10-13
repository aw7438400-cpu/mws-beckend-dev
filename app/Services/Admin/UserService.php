<?php

namespace App\Services\Admin;

use Throwable;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserPassword;
use App\Traits\HasBasicCrud;
use App\Traits\HasFileUpload;
use App\Traits\HasBasicSearch;
use App\Traits\HasHttpResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService.
 */
class UserService
{
    use HasBasicCrud, HasHttpResponse, HasFileUpload, HasBasicSearch;

    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function searchUser(array $relation = [], int $perPage = 10, ?string $query = null)
    {
        $queryBuilder = $this->model->with($relation)
            ->whereHas('userDetail', function ($q) use ($query) {
                $q->whereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", ["%{$query}%"]);
            });

        return $queryBuilder->latest()->paginate($perPage);
    }


    public function registerUser(array $data)
    {
        try {
            DB::beginTransaction();

            $user = $this->createUser($data);

            $divisionName = $userDetail->division->name ?? 'User';

            // Buat role jika belum ada
            if (!Role::where('name', $divisionName)->where('guard_name', 'web')->exists()) {
                Role::create([
                    'name' => $divisionName,
                    'guard_name' => 'web' // Penting! Sesuai dengan guard yang digunakan
                ]);
            }

            // Assign role ke user berdasarkan nama division
            $user->assignRole($divisionName);

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateUser(string $uuid, array $data)
    {
        try {
            DB::beginTransaction();

            // Ambil user dan detailnya
            $user = $this->findByUuid($uuid);
            $userDetail = $user->userDetail;

            // Simpan division lama untuk mengecek perubahan
            $oldDivisionName = $userDetail->division->name ?? 'User';

            // Update user
            $user->update([
                'company_id' => $data['company_id'] ?? $user->company_id,
                'username' => $data['username'] ?? $user->username,
                'email' => $data['email'] ?? $user->email,
            ]);

            // Update detail user
            $userDetail->update([
                'division_id' => $data['division'] ?? $userDetail->division_id,
                'first_name' => $data['first_name'] ?? $userDetail->first_name,
                'middle_name' => $data['middle_name'] ?? $userDetail->middle_name,
                'last_name' => $data['last_name'] ?? $userDetail->last_name,
                'gender' => $data['gender'] ?? $userDetail->gender,
                'phone' => $data['phone'] ?? $userDetail->phone,
            ]);

            // Reload userDetail untuk mendapatkan division terbaru
            $userDetail->refresh();

            // Ambil nama division baru setelah update
            $newDivisionName = $userDetail->division->name ?? 'User';

            // Jika division berubah, update role
            if ($oldDivisionName !== $newDivisionName) {
                // Buat role jika belum ada
                if (!Role::where('name', $newDivisionName)->exists()) {
                    Role::create(['name' => $newDivisionName]);
                }

                // Assign role baru langsung
                $user->syncRoles([$newDivisionName]);
            }

            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function createUser(array $data): User
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
        ]);
    }
    
}
