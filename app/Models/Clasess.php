<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clasess extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    // Primary key diubah ke 'nisn', bukan id
    protected $primaryKey = 'nisn';
    public $incrementing = false; // karena string, bukan auto-increment
    protected $keyType = 'string'; // tipe string
    protected $guarded = []; // bisa mass assign semua kolom

    // ================= Relasi =================
    public function admissions()
    {
        return $this->hasMany(Admission::class, 'class_id', 'nisn');
    }

    public function students()
    {
        return $this->hasMany(\App\Models\User::class, 'class_id', 'nisn');
    }
}
