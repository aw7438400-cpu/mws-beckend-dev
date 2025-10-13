<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    // Table name jika tidak sesuai konvensi (opsional)
    protected $table = 'skills';

    // Primary key type UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Fillable fields
    protected $fillable = [
        'name',
        'description',
    ];

    // Casts
    protected $casts = [
        'id' => 'string',
    ];

    // Boot method untuk otomatis generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi ke student_skills
     */
    public function studentSkills()
    {
        return $this->hasMany(StudentSkill::class, 'skill_id', 'id');
    }
}
