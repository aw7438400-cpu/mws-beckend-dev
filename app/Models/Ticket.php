<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    // Jika table menggunakan UUID
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'tickets';

    protected $fillable = [
        'requester_id',
        'asset_id',
        'issue_type',
        'description',
        'priority',
        'status',
        'assigned_to',
        'opened_at',
        'closed_at',
    ];

    protected $dates = [
        'opened_at',
        'closed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Enum constants untuk kemudahan penggunaan
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    /**
     * Relasi ke user yang membuat ticket
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Relasi ke user yang ditugaskan
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relasi ke aset yang terkait
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /**
     * Scope untuk ticket berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk ticket berdasarkan prioritas
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
