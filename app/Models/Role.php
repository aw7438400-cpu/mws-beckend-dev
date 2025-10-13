<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Str;

class Role extends SpatieRole
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($role) {
            if (empty($role->uuid)) {
                $role->uuid = (string) Str::uuid();
            }
        });
    }
}
