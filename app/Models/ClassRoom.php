<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'name',
        'type',
        'capacity',
        'note',
    ];

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'class_id');
    }
}
