<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleException extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedule_exceptions';

    // Primary key tipe UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Mass assignment
    protected $fillable = [
        'schedule_id',
        'exception_date',
        'reason',
        'status',
        'rescheduled_to',
        'updated_by',
    ];

    // Casting
    protected $casts = [
        'exception_date' => 'date',
        'rescheduled_to' => 'datetime',
        'status' => 'string', // enum handled manually or via accessor
    ];

    /**
     * Relasi ke Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    /**
     * Relasi ke User yang melakukan update
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Accessor untuk enum status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
