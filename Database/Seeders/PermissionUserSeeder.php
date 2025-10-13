<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class PermissionUserSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'sanctum';

        // Daftar permission
        $permissions = [
            'index user',
            'get user',
            'create user',
            'update user',
            'delete user',
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard],
                ['uuid' => (string) Str::uuid()]
            );
        }

        // ================= Admin =================
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );
        $adminRole->givePermissionTo(Permission::where('guard_name', $guard)->get());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$adminUser->hasRole('Admin')) {
            $adminUser->assignRole($adminRole);
        }

        // ================= Teacher =================
        $teacherRole = Role::firstOrCreate(
            ['name' => 'Teacher', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );
        // Assign hanya permission tertentu untuk Teacher
        $teacherPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
        ])->get();
        $teacherRole->givePermissionTo($teacherPermissions);

        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$teacherUser->hasRole('Teacher')) {
            $teacherUser->assignRole($teacherRole);
        }

        // ================= Student =================
        $studentRole = Role::firstOrCreate(
            ['name' => 'Student', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );
        // Assign hanya permission tertentu untuk Student
        $studentPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
        ])->get();
        $studentRole->givePermissionTo($studentPermissions);

        $studentUser = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$studentUser->hasRole('Student')) {
            $studentUser->assignRole($studentRole);
        }
    }
}
