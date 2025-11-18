<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['date', 'activity', 'student_id', 'mentor_id', 'meta'];
    protected $casts = ['meta' => 'array', 'date' => 'datetime'];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function mentor()
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }
}
