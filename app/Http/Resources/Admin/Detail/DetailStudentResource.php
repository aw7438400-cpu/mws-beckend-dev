<?php

namespace App\Http\Resources\Admin\Detail;

use Illuminate\Http\Request;
use App\Http\Resources\Admin\Index\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailStudentResource extends JsonResource
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
            'student_name' => $this->student_name,
            'grade' => $this->grade,
            'tier' => $this->tier,
            'type' => $this->type,
            'mentor' => new UserResource($this->whenLoaded('mentor')),
            'progress_status' => $this->progress_status,
            'strategy' => $this->strategy
        ];
    }
}
