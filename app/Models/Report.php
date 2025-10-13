<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reports';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'student_id',
        'class_id',
        'report_type',
        'template_id',
        'narrative',
        'status',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot function to auto-generate UUID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship: Report belongs to a Student (User)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relationship: Report belongs to a Class
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Relationship: Report belongs to a Template
     */
    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'template_id');
    }

    /**
     * Relationship: Report updated by User
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relationship: Report has many Signoffs
     */
    public function signoffs()
    {
        return $this->hasMany(ReportSignoff::class, 'report_id');
    }

    /**
     * Relationship: Report has many History Logs
     */
    public function historyLogs()
    {
        return $this->hasMany(ActivityLog::class, 'report_id');
    }
}
