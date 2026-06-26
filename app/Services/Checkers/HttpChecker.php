<?php

namespace App\Services\Checkers;

use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\Http;

class HttpChecker implements CheckerInterface
{
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function check(): array
    {
        $url = $this->service->endpoint;
        if (!$url) {
            return ['status' => 'down', 'response_time_ms' => null, 'message' => 'No endpoint configured (requires full URL).'];
        }

        $start = microtime(true);
        try {
            $response = Http::timeout(10)->get($url);
            $latency = (microtime(true) - $start) * 1000;

            if ($response->successful()) {
                return [
                    'status' => 'up',
                    'response_time_ms' => $latency,
                    'message' => "HTTP {$response->status()}"
                ];
            }

            return [
                'status' => 'down',
                'response_time_ms' => $latency,
                'message' => "HTTP {$response->status()}"
            ];
        } catch (Exception $e) {
            return [
                'status' => 'down',
                'response_time_ms' => (microtime(true) - $start) * 1000,
                'message' => $e->getMessage()
            ];
        }
    }
}
