<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmotionalCheckin extends Model
{
    use HasFactory;

    protected $table = 'emotional_checkins'; // mapping ke tabel

    protected $fillable = [
        'user_id',
        'role',
        'mood',
        'intensity',
        'note',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'intensity' => 'integer',
    ];

    /**
     * Relasi ke model User
     * Setiap check-in dilakukan oleh satu user.
     */

    protected $keyType = 'string'; // UUID support
    public $incrementing = false; // UUID tidak auto-increment


    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Accessor tambahan: memberi label mood dengan emoji misalnya
     */
    public function getMoodLabelAttribute()
    {
        return match ($this->mood) {
            'very_happy' => '😊 Very Happy',
            'happy' => '🙂 Happy',
            'neutral' => '😐 Neutral',
            'sad' => '😢 Sad',
            'stressed' => '😣 Stressed',
            'angry' => '😡 Angry',
            default => ucfirst($this->mood),
        };
    }
}
