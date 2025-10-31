<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressUpdate extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'assignment_id', 'updated_by', 'note', 'metrics'];

    protected $casts = [
        'metrics' => 'array',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(InterventionAssignment::class, 'assignment_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
