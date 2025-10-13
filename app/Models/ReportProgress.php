<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportProgress extends Model
{
    // Jika tabel berbeda dari nama model (default: report_progresses)
    protected $table = 'report_signoffs';

    // Primary key tipe UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Mass assignable attributes
    protected $fillable = [
        'report_id',
        'role',
        'signed_by',
        'signed_at',
    ];

    // Casts
    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Relasi ke Report
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    /**
     * Relasi ke User yang menandatangani
     */
    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by', 'id');
    }

    /**
     * Cek apakah report sudah signed
     */
    public function isSigned(): bool
    {
        return !is_null($this->signed_at) && !is_null($this->signed_by);
    }

    /**
     * Ambil semua tanda tangan untuk report tertentu
     */
    public static function getProgressForReport($reportId)
    {
        return self::where('report_id', $reportId)
            ->orderBy('role') // urut berdasarkan role
            ->get();
    }
}
