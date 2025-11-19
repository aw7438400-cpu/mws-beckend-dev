<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    protected $fillable = ['title','description','category','created_by','is_public'];
    public function creator() { return $this->belongsTo(Teacher::class,'created_by'); }
}


