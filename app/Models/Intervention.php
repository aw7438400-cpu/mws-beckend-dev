<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intervention extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'description', 'default_duration_days', 'recommended_for'];

    protected $casts = [
        'recommended_for' => 'array',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(InterventionAssignment::class);
    }
}
