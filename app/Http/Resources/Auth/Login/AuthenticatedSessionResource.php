<?php

namespace App\Http\Resources\Auth\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token->plainTextToken,
            'refresh_token' => $this->refreshToken,
            'is_remember' => $this->is_remember,
            'company' => [
                'id' => $this->company->id ?? null,
                'label' => $this->company->name ?? null,
            ]
        ];
    }
}
