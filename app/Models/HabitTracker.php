<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HabitTracker extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * Tabel yang digunakan model ini
     */
    protected $table = 'habit_trackers';

    /**
     * Primary key menggunakan UUID
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Kolom yang bisa diisi (mass assignment)
     */
    protected $fillable = [
        'student_id',
        'habit_type',
        'target_value',
        'achieved_value',
        'date',
        'note',
    ];

    /**
     * Kolom bertipe tanggal
     */
    protected $dates = [
        'date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Enum habit_type sesuai skema database
     */
    const HABIT_TYPES = [
        'sleep',
        'reading',
        'goal',
        'exercise',
        'study',
    ];

    /**
     * Relasi: HabitTracker dimiliki oleh satu User (student)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Accessor: hitung persentase pencapaian habit
     */
    public function getProgressAttribute(): float
    {
        if ($this->target_value <= 0) {
            return 0.0;
        }

        return round(($this->achieved_value / $this->target_value) * 100, 2);
    }
}
