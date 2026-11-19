<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCaseReply extends Model
{
    protected $fillable = ['health_case_id', 'user_id', 'body'];

    public function healthCase()
    {
        return $this->belongsTo(HealthCase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
