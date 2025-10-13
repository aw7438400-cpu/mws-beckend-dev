<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Badge extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name
     */
    protected $table = 'badges';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'name',
        'code',
        'category',
        'description',
        'icon_url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot function to generate UUID automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi: Satu badge dapat dimiliki banyak student_badges.
     */
    public function studentBadges()
    {
        return $this->hasMany(StudentBadge::class, 'badge_id');
    }

    /**
     * Relasi: Banyak siswa melalui student_badges.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_badges', 'badge_id', 'student_id')
            ->withTimestamps()
            ->withPivot(['awarded_by', 'awarded_at', 'awarded_date', 'evidence_url', 'note']);
    }
}
