<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Profile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * Primary key bukan auto increment (karena UUID).
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'gender',
        'date_of_birth',
        'phone',
        'address',
        'avatar_url',
    ];

    /**
     * Atribut yang dikonversi secara otomatis ke tipe tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relasi ke model User.
     * Profile dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate UUID saat membuat record baru.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
