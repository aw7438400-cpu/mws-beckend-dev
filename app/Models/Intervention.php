<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'student_id', 'group_code', 'intervention_type', 'strategy', 'progress_status', 'notes', 'created_by'];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function creator()
    {
        return $this->belongsTo(Teacher::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
