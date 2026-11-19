<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{
    protected $fillable = [
        'listing_id',
        'requester_id',
        'status',
        'message',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
