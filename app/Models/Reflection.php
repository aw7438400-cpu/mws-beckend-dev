<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reflection extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'reflections';
    protected $keyType = 'string'; // karena id adalah UUID
    public $incrementing = false;

    protected $fillable = [
        'id',
        'portfolio_item_id',
        'author_id',
        'author_role',
        'content',
        'reflection_date',
    ];

    protected $casts = [
        'reflection_date' => 'datetime',
    ];

    /**
     * Relasi: Reflection belongs to PortfolioItem
     */
    public function portfolioItem()
    {
        return $this->belongsTo(PortfolioItem::class, 'portfolio_item_id');
    }

    /**
     * Relasi: Reflection belongs to User (author)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
