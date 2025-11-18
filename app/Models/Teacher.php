<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'role'];
    public function students()
    {
        return $this->hasMany(Student::class, 'mentor_id');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class, 'mentor_id');
    }
}
