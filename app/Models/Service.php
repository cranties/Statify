<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'server_id',
        'name',
        'type',
        'port',
        'endpoint',
        'keyword',
        'credentials',
        'check_interval_minutes',
        'failure_threshold',
        'success_threshold',
        'status',
        'consecutive_failures',
        'consecutive_successes',
        'last_checked_at',
        'last_status_change_at',
        'is_active',
        'notify_telegram',
        'notify_email',
    ];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
        'notify_telegram' => 'boolean',
        'notify_email' => 'boolean',
        'last_checked_at' => 'datetime',
        'last_status_change_at' => 'datetime',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function checkHistories()
    {
        return $this->hasMany(CheckHistory::class);
    }
}
