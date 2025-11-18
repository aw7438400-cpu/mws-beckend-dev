<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'students';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'photo_id',
        'student_name',
        'gender',
        'student_mws_email',
        'grade',
        'tier',
        'type',
        'join_academic_year',
        'class_name',
        'nisn',
        'status',
        'mentor_id',
        'strategy',
        'progress_status'
    ];
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    public function mentor()
    {
        return $this->belongsTo(Teacher::class, 'mentor_id');
    }
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
    public function emotionalCheckins()
    {
        return $this->hasMany(EmotionalCheckin::class);
    }
}
