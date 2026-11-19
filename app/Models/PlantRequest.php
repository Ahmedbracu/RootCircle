<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'requested_plant_or_seed',
        'description',
        'status',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function responses()
    {
        return $this->hasMany(PlantRequestResponse::class);
    }
}
