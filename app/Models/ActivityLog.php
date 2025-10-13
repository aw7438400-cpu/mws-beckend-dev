<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'activity_logs';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Kolom yang bisa diisi secara mass-assignment.
     */
    protected $fillable = [
        'id',
        'user_id',
        'action',
        'target_type',
        'target_id',
        'meta',
        'expired_at',
        'created_at',
    ];

    /**
     * Tabel ini tidak memiliki kolom updated_at, jadi kita nonaktifkan timestamps penuh.
     */
    public $timestamps = false;

    /**
     * Casting otomatis agar tipe data sesuai dengan definisi database.
     */
    protected $casts = [
        'meta' => 'array',
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke model User.
     * Setiap activity log dibuat oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Jika kamu ingin activity log bisa mereferensikan entitas lain
     * (misalnya Reports, Portfolio, dsb), gunakan morph relation.
     */
    public function target()
    {
        return $this->morphTo(__FUNCTION__, 'target_type', 'target_id');
    }
}
