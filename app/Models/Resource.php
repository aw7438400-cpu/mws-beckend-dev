<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resources';

    /**
     * Primary key type and incrementing
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'url',
        'mime_type',
        'file_size',
        'uploaded_by',
        'uploaded_at',
    ];

    /**
     * Date casts
     */
    protected $dates = [
        'uploaded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relation: resource uploaded by a user
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
