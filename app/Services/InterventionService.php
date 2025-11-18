<?php

namespace App\Services;



use App\Models\Intervention;
use Illuminate\Support\Facades\DB;

class InterventionService
{
    public function createIntervention(array $data)
    {
        return Intervention::create($data);
    }
}
