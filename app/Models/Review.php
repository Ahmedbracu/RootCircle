<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'reviewer_id',
        'nursery_id',
        'rating',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function nursery()
    {
        return $this->belongsTo(NurseryProfile::class, 'nursery_id');
    }
}
