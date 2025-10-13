<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admission extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'class_students';

    protected $fillable = [
        'class_id',
        'student_id',
        'enrolled_at',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'status' => 'string', // enum: enrolled, dropped, completed
    ];

    /**
     * Relasi ke model Class (kelas)
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Relasi ke model User (siswa)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
