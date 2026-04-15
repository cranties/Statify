<?php

namespace App\Services\Checkers;

use App\Models\Service;
use Exception;

class TcpHandshakeChecker implements CheckerInterface
{
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function check(): array
    {
        $host = $this->service->server->ip_address;
        $port = $this->service->port;

        if (!$host || !$port) {
            return ['status' => 'down', 'response_time_ms' => null, 'message' => 'Host or Port missing'];
        }

        $start = microtime(true);
        $fp = @fsockopen($host, $port, $errno, $errstr, 5);
        $latency = (microtime(true) - $start) * 1000;

        if ($fp) {
            fclose($fp);
            return [
                'status' => 'up',
                'response_time_ms' => $latency,
                'message' => "TCP Port {$port} is open"
            ];
        }

        return [
            'status' => 'down',
            'response_time_ms' => $latency,
            'message' => "TCP Connection failed: $errstr ($errno)"
        ];
    }
}
