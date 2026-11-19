<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantRequestResponse extends Model
{
    protected $fillable = [
        'plant_request_id',
        'responder_id',
        'listing_id',
        'message',
        'status',
    ];

    public function plantRequest()
    {
        return $this->belongsTo(PlantRequest::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
