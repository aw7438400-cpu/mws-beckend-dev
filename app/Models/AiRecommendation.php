<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRecommendation extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'report_id', 'model', 'recommendation', 'confidence'];

    protected $casts = [
        'recommendation' => 'array',
        'confidence' => 'float',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(BaselineReport::class, 'report_id');
    }
}
