<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerStat extends Model
{
    protected $fillable = [
        'server_id',
        'cpu_usage',
        'ram_usage',
        'ram_total',
        'ram_used',
        'disk_usage',
        'disk_total',
        'disk_used',
        'uptime',
        'health_status',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
