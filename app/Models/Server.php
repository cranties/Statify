<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'description',
        'os',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function stats()
    {
        return $this->hasMany(ServerStat::class);
    }

    public function latestStat()
    {
        return $this->hasOne(ServerStat::class)->latestOfMany();
    }
}
