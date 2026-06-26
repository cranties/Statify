<?php

namespace App\Services\Checkers;

use App\Models\Service;
use Exception;

class PingChecker implements CheckerInterface
{
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function check(): array
    {
        $host = $this->service->endpoint ?: $this->service->server->ip_address;
        
        try {
            $ping = new \JJG\Ping($host);
            $latency = $ping->ping('exec');
            if ($latency !== false) {
                return [
                    'status' => 'up',
                    'response_time_ms' => $latency,
                    'message' => 'OK'
                ];
            }
            return [
                'status' => 'down',
                'response_time_ms' => null,
                'message' => 'Ping failed'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'down',
                'response_time_ms' => null,
                'message' => $e->getMessage(),
            ];
        }
    }
}
