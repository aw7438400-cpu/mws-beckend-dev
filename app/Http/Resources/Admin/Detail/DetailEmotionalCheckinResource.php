<?php

namespace App\Http\Resources\Admin\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailEmotionalCheckinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'mood' => $this->mood,
            'intensity' => $this->intensity,
            'note' => $this->note,
            'checked_in_at' => $this->checked_in_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Tampilkan relasi user hanya di sini
            'user' => $this->whenLoaded('user', [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
        ];
    }
}