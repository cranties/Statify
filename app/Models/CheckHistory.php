<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckHistory extends Model
{
    protected $fillable = [
        'service_id',
        'status',
        'response_time_ms',
        'message',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
