<?php

namespace App\Http\Resources\Admin\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmotionalCheckinResource extends JsonResource
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
            'user_id' => $this->user_id,
            'role' => $this->role,
            'mood' => $this->mood,
            'intensity' => $this->intensity,
            'note' => $this->note,
            'checked_in_at' => $this->checked_in_at,
            'created_at' => $this->created_at,
        ];
    }
}