<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCase extends Model
{
    protected $fillable = [
        'user_id',
        'plant_id',
        'title',
        'symptoms',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function updates()
    {
        return $this->hasMany(HealthCaseUpdate::class);
    }

    public function replies()
    {
        return $this->hasMany(HealthCaseReply::class);
    }
}
