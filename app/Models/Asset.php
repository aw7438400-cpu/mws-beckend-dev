<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Nama tabel di database
     */
    protected $table = 'assets';

    /**
     * Primary key menggunakan UUID
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Kolom yang bisa diisi (mass assignable)
     */
    protected $fillable = [
        'name',
        'category',
        'serial_number',
        'location',
        'status',
        'purchase_date',
        'note',
    ];

    /**
     * Kolom bertipe tanggal
     */
    protected $dates = [
        'purchase_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Default value untuk kolom tertentu
     */
    protected $attributes = [
        'status' => 'active',
    ];

    /**
     * Relasi: satu asset bisa punya banyak tiket (tickets)
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'asset_id');
    }

    /**
     * Scope untuk filter asset berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
