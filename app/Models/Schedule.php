<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedules';
    protected $keyType = 'string';
    public $incrementing = false; // UUID primary key

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    // ----------------------
    // RELATIONSHIPS
    // ----------------------

    // Relasi ke Class
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id'); // Model SchoolClass diasumsikan
    }

    // Relasi ke Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Relasi ke Teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke User yang melakukan update
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke enrollments (siswa yang ikut)
    public function enrollments()
    {
        return $this->hasMany(ScheduleEnrollment::class, 'schedule_id');
    }

    // Relasi ke schedule exceptions
    public function exceptions()
    {
        return $this->hasMany(ScheduleException::class, 'schedule_id');
    }

    // ----------------------
    // SCOPES / HELPERS
    // ----------------------

    // Scope aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan hari
    public function scopeDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}
