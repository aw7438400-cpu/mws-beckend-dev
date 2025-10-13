<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;



trait HasHashedPassword
{
    protected $model;

    protected $userPasswordModel;

    public function setPassword($model, $userPasswordModel = null)
    {
        $this->model = $model;

        $this->userPasswordModel = $userPasswordModel;
    }

    // Membuat entitas dengan password yang di-hash
    public function createWithHashedPassword(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->model->create($data);
    }

    // Memperbarui entitas dengan id dan password yang di-hash
    public function updateWithHashedPasswordById(int $id, array $data)
    {
        $entity = $this->model->findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $entity->update($data);
        return $entity;
    }

    // Memperbarui entitas dengan UUID dan password yang di-hash
    public function updateWithHashedPasswordByUuid(string $uuid, array $data)
    {
        $entity = $this->model->where('uuid', $uuid)->first();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $entity->update($data);
        return $entity;
    }

    public function updateWithRelationHashedPasswordByUuid(string $uuid, array $data)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();

        if (isset($data['password'])) {
            $hashedPassword = Hash::make($data['password']);
        }

        if (isset($data['password'])) {
            $this->userPasswordModel->create([
                'user_id' => $user->id,
                'password' => $hashedPassword
            ]);
        }

        return $user;
    }

    public function updateWithRelationCheckHashedPasswordByUuid(string $uuid, array $data)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();

        // Validasi password lama
        if (isset($data['old_password']) && isset($data['password'])) {
            $oldPassword = $data['old_password'];
            $currentPassword = $this->userPasswordModel
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!Hash::check($oldPassword, $currentPassword->password)) {
                throw new \InvalidArgumentException("Old password doesn't match.");
            }
        }

        // Hash password baru jika ada
        if (isset($data['password'])) {
            $hashedPassword = Hash::make($data['password']);

            // Simpan password baru
            $this->userPasswordModel->create([
                'user_id' => $user->id,
                'password' => $hashedPassword
            ]);
        }

        return $user;
    }
}
