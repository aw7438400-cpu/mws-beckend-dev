<?php

namespace App\Http\Resources\Admin\detail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_active_intervention_students' => $this['total_active_intervention_students'],
            'total_active_mentors' => $this['total_active_mentors'],
            'intervention_target_percentage' => $this['intervention_target_percentage'],
            'total_mtss_students' => $this['total_mtss_students'],
        ];
}
}
