<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportTemplate extends Model
{
    use HasFactory, SoftDeletes;

    // Jika primary key menggunakan UUID
    protected $keyType = 'string';
    public $incrementing = false;

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'scope',
        'description',
    ];

    // Casting enum
    protected $casts = [
        'scope' => 'string', // bisa buat enum cast manual jika diperlukan
    ];

    // Boot method untuk generate UUID otomatis
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi: Template digunakan di banyak report
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'template_id', 'id');
    }
}
