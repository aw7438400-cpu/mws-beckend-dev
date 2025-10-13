<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * Table name (optional, if different from plural form)
     */
    protected $table = 'portfolios';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'title',
        'description',
        'visibility',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'visibility' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Portfolio belongs to a student (User)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    /**
     * Relationship: Portfolio has many portfolio items
     */
    public function items()
    {
        return $this->hasMany(PortfolioItem::class, 'portfolio_id', 'id');
    }

    /**
     * Accessor (optional): Format title
     */
    public function getTitleAttribute($value)
    {
        return ucwords($value);
    }
}
