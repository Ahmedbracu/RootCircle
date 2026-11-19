<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseryProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nursery_name',
        'description',
        'address',
        'verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'verified'    => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'nursery_id');
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
