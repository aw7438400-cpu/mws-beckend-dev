<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'portfolio_items';
    public $incrementing = false; // Karena id bertipe UUID
    protected $keyType = 'string';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     */
    protected $fillable = [
        'id',
        'portfolio_id',
        'title',
        'description',
        'item_type',
        'resource_id',
        'meta',
    ];

    /**
     * Tipe data otomatis dikonversi oleh Eloquent.
     */
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke model Portfolio (Many-to-One)
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class, 'portfolio_id');
    }

    /**
     * Relasi ke model Resource (Many-to-One)
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    /**
     * Relasi ke model Reflection (One-to-Many)
     */
    public function reflections()
    {
        return $this->hasMany(Reflection::class, 'portfolio_item_id');
    }
}
