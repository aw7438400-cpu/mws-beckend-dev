<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentBadge extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'student_badges';
    protected $primaryKey = 'id';
    public $incrementing = false; // karena UUID
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'badge_id',
        'awarded_by',
        'awarded_at',
        'awarded_date',
        'evidence_url',
        'note',
    ];

    protected $dates = [
        'awarded_at',
        'awarded_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relasi ke Student (User)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi ke Badge
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }

    /**
     * Relasi ke User yang memberikan badge
     */
    public function awardedBy()
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
