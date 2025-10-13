<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Support\Str;

class Permission extends SpatiePermission
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($permission) {
            if (empty($permission->uuid)) {
                $permission->uuid = (string) Str::uuid();
            }
        });
    }
}
