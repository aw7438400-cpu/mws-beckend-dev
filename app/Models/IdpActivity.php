<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IdpActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'idp_activities';

    /**
     * Primary key menggunakan UUID.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Kolom yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'id',
        'staff_id',
        'category',
        'title',
        'hours',
        'description',
        'activity_date',
        'evidence_url',
    ];

    /**
     * Tipe data kolom tertentu.
     */
    protected $casts = [
        'hours' => 'decimal:2',
        'activity_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi: setiap IDP Activity dimiliki oleh satu staff (user).
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
