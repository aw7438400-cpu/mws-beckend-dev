<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students'; // pastikan tabel benar

    protected $fillable = [
        'nisn',
        'photo_id',
        'full_name',
        'nick_name',
        'gender',
        'current_status',
        'student_mws_email',
        'current_grade',
        'class_name',
        'join_academic_year',
    ];
}
