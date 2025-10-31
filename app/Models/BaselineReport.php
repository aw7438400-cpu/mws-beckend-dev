<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BaselineReport extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'student_id', 'assessment_id', 'total_score', 'summary', 'generated_by'];

    protected $casts = [
        'summary' => 'array',
        'total_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function tier(): HasOne
    {
        return $this->hasOne(TierClassification::class, 'report_id');
    }

    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class, 'report_id');
    }
}
