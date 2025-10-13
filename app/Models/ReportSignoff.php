<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReportSignoff extends Model
{
    use HasUuids;
    // use SoftDeletes; // aktifkan jika kamu ingin support deleted_at

    protected $table = 'report_signoffs';

    protected $keyType = 'string'; // karena UUID
    public $incrementing = false; // UUID tidak auto-increment

    protected $fillable = [
        'report_id',
        'role',
        'signed_by',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'role' => 'string', // enum akan disimpan sebagai string
    ];

    // Relasi ke laporan
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    // Relasi ke user yang menandatangani
    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by', 'id');
    }
}
