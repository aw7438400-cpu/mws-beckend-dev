<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parents_students';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'parent_id',
        'student_id',
        'relationship',
        'can_view_portfolio',
        'can_receive_reports',
    ];

    protected $casts = [
        'can_view_portfolio' => 'boolean',
        'can_receive_reports' => 'boolean',
    ];

    /**
     * Relasi ke model User sebagai orang tua.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Relasi ke model User sebagai murid.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
