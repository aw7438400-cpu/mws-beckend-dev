<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notifications';

    protected $keyType = 'string'; // Karena pakai UUID
    public $incrementing = false;  // Non auto increment

    protected $fillable = [
        'user_id',
        'type',
        'payload',
        'is_read',
        'expired_at',
        'delivered_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_read' => 'boolean',
        'expired_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Relasi ke User (banyak notifikasi dimiliki oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
