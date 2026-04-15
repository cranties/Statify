<?php

namespace App\Services\Checkers;

use App\Models\Service;
use Exception;

class CheckerFactory
{
    public static function make(Service $service): CheckerInterface
    {
        return match ($service->type) {
            'ping' => new PingChecker($service),
            'http', 'https' => new HttpChecker($service),
            'tcp', 'mysql', 'postgres', 'redis', 'ssh', 'smb' => new TcpHandshakeChecker($service),
            // 'dns' => new DnsChecker($service),
            // 'ssl' => new SslChecker($service),
            // 'keyword' => new KeywordChecker($service),
            // 'snmp' => new SnmpChecker($service),
            default => throw new Exception("Unknown service type: {$service->type}"),
        };
    }
}
