<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierClassification extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'report_id', 'tier', 'explanation', 'metrics'];

    protected $casts = [
        'explanation' => 'array',
        'metrics' => 'array',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(BaselineReport::class, 'report_id');
    }
}
