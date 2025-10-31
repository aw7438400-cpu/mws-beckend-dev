<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterventionAssignment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'intervention_id',
        'student_id',
        'assigned_by',
        'report_id',
        'starts_at',
        'ends_at',
        'status'
    ];

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ProgressUpdate::class, 'assignment_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(BaselineReport::class, 'report_id');
    }
}
