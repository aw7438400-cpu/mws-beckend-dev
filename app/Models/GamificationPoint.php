<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamificationPoint extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'points', 'badges', 'streak_days', 'last_checkin_at'];
    protected $casts = ['badges' => 'array', 'last_checkin_at' => 'datetime'];
}
