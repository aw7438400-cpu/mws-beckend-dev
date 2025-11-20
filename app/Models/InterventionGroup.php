<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterventionGroup extends Model
{
    protected $fillable = [
        'group_name',
        'description',
        'created_by'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'intervention_group_students');
    }
}
