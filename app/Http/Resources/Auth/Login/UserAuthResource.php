<?php

namespace App\Http\Resources\Auth\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->username,
            'role' => $this->getRoleNames(),
            'permissions' => $this->getAllPermissions()->pluck('name')
        ];
    }
}
