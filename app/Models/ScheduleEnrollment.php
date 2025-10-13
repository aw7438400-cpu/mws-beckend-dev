<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleEnrollment extends Model
{
    use SoftDeletes;

    protected $table = 'schedule_enrollments';

    // Primary key adalah UUID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'schedule_id',
        'student_id',
        'enrolled_at',
        'updated_by',
    ];

    protected $dates = [
        'enrolled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relasi ke Schedule
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    /**
     * Relasi ke User sebagai student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    /**
     * Relasi ke User yang melakukan update terakhir
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
