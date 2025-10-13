<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    // Gunakan UUID sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false;

    // Nama tabel (opsional jika mengikuti konvensi Laravel)
    protected $table = 'subjects';

    // Mass assignable
    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'category',
    ];

    // Cast untuk enum jika ingin memudahkan
    protected $casts = [
        'category' => 'string', // Laravel tidak native enum, bisa pakai string
    ];

    // Boot method untuk otomatis generate UUID saat create
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
     * Relasi: Subject bisa dimiliki banyak schedules
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id', 'id');
    }
}
