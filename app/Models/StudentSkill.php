<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSkill extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'student_skills';

    protected $primaryKey = 'id';

    public $incrementing = false; // UUID, bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'skill_id',
        'level',
        'evidence',
        'evaluated_at',
    ];

    protected $dates = [
        'evaluated_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relasi ke student (User)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi ke skill
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
