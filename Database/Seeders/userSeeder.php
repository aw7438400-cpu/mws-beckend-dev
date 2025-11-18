<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Guard untuk Spatie Permission
        $guard = 'sanctum';

        // Daftar role MTSS
        $roles = ['admin', 'principal', 'teacher', 'mentor', 'student', 'parent'];

        // 1️⃣ Buat role SANCTUM (WAJIB sebelum assignRole)
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => $guard]
            );
        }

        // 2️⃣ Buat user & assignRole
        foreach ($roles as $roleName) {
            $user = User::firstOrCreate(
                ['email' => $roleName . '@mtss.sch.id'],
                [
                    'uuid' => Str::uuid(),
                    'name' => ucfirst($roleName),
                    'password' => bcrypt('password123'),
                ]
            );

            // Assign role SANCTUM
            $user->syncRoles([$roleName]); // lebih aman daripada assignRole
        }

        $this->command->info('✅ Users & roles seeded dengan guard sanctum.');
    }
}
