<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReportFile extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'report_files'; // pastikan nama tabel sesuai migrasi
    public $incrementing = false; // karena pakai UUID
    protected $keyType = 'string';

    protected $fillable = [
        'report_id',
        'title',
        'description',
        'file_url',
        'mime_type',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $dates = [
        'uploaded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relasi ke Report
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    /**
     * Relasi ke User yang mengunggah file
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
}
