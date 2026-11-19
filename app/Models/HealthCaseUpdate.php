<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCaseUpdate extends Model
{
    protected $fillable = ['health_case_id', 'photo_url', 'note'];

    public function healthCase()
    {
        return $this->belongsTo(HealthCase::class);
    }
}
