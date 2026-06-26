<?php

namespace App\Services\Checkers;

use App\Models\Service;
use App\Models\ServerStat;
use phpseclib3\Net\SSH2;
use Exception;

class SshChecker implements CheckerInterface
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function check(): array
    {
        $startTime = microtime(true);
        $server = $this->service->server;
        $host = $server->ip_address;
        $port = $this->service->port ?: 22;
        $username = $this->service->credentials['username'] ?? '';
        $password = $this->service->credentials['password'] ?? '';

        try {
            // 1. Establish connection and login
            $ssh = new SSH2($host, $port, 5); // 5 seconds timeout
            if (!$ssh) {
                throw new Exception("Could not connect to SSH on {$host}:{$port}");
            }

            if (!$ssh->login($username, $password)) {
                throw new Exception("SSH login failed for user '{$username}'");
            }

            // 2. Retrieve Stats
            // Get RAM Stats (free -m)
            $ramOutput = $ssh->exec('free -m');
            $ramStats = $this->parseRam($ramOutput);

            // Get Disk Stats (df -m /)
            $diskOutput = $ssh->exec('df -m /');
            $diskStats = $this->parseDisk($diskOutput);

            // Get CPU Stats (top -bn1)
            $cpuOutput = $ssh->exec('top -bn1');
            $cpuUsage = $this->parseCpu($cpuOutput);

            // Get Uptime (cat /proc/uptime)
            $uptimeOutput = $ssh->exec('cat /proc/uptime');
            $uptimeString = $this->parseUptime($uptimeOutput);

            // Determine overall health status
            $healthStatus = 'healthy';
            if ($cpuUsage > 90 || $ramStats['usage_pct'] > 90 || $diskStats['usage_pct'] > 90) {
                $healthStatus = 'critical';
            } elseif ($cpuUsage > 75 || $ramStats['usage_pct'] > 75 || $diskStats['usage_pct'] > 75) {
                $healthStatus = 'warning';
            }

            // Save stats to database
            ServerStat::create([
                'server_id' => $server->id,
                'cpu_usage' => $cpuUsage,
                'ram_usage' => $ramStats['usage_pct'],
                'ram_total' => $ramStats['total_gb'],
                'ram_used' => $ramStats['used_gb'],
                'disk_usage' => $diskStats['usage_pct'],
                'disk_total' => $diskStats['total_gb'],
                'disk_used' => $diskStats['used_gb'],
                'uptime' => $uptimeString,
                'health_status' => $healthStatus,
            ]);

            $responseTime = round((microtime(true) - $startTime) * 1000);

            return [
                'status' => 'up',
                'response_time_ms' => $responseTime,
                'message' => "SSH Connected. CPU: {$cpuUsage}%, RAM: {$ramStats['usage_pct']}%, Disk: {$diskStats['usage_pct']}%",
            ];

        } catch (Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000);
            return [
                'status' => 'down',
                'response_time_ms' => $responseTime,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function parseRam(string $output): array
    {
        // Match the line starting with "Mem:"
        if (preg_match('/Mem:\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $output, $matches)) {
            $totalMb = (float)$matches[1];
            $usedMb = (float)$matches[2];
            $availableMb = (float)$matches[6]; // Modern free -m available column

            $actualUsedMb = $totalMb - $availableMb;
            if ($actualUsedMb < 0) {
                $actualUsedMb = $usedMb; // Fallback
            }

            $usagePct = round(($actualUsedMb / $totalMb) * 100, 1);
            return [
                'total_gb' => round($totalMb / 1024, 2),
                'used_gb' => round($actualUsedMb / 1024, 2),
                'usage_pct' => $usagePct,
            ];
        }

        return ['total_gb' => 0, 'used_gb' => 0, 'usage_pct' => 0];
    }

    protected function parseDisk(string $output): array
    {
        $lines = explode("\n", trim($output));
        if (count($lines) >= 2) {
            $dataLine = trim($lines[1]);
            $dataLine = preg_replace('/\s+/', ' ', $dataLine);
            $parts = explode(' ', $dataLine);
            if (count($parts) >= 5) {
                $totalMb = (float)$parts[1];
                $usedMb = (float)$parts[2];
                $freeMb = (float)$parts[3];
                $usagePct = (float)str_replace('%', '', $parts[4]);

                return [
                    'total_gb' => round($totalMb / 1024, 2),
                    'used_gb' => round($usedMb / 1024, 2),
                    'usage_pct' => $usagePct,
                ];
            }
        }
        return ['total_gb' => 0, 'used_gb' => 0, 'usage_pct' => 0];
    }

    protected function parseCpu(string $output): float
    {
        if (preg_match('/(\d+[\.,]\d+)\s*id/', $output, $matches)) {
            $idle = (float)str_replace(',', '.', $matches[1]);
            $usage = 100 - $idle;
            return round($usage, 1);
        }
        return 0.0;
    }

    protected function parseUptime(string $output): string
    {
        $parts = explode(' ', trim($output));
        if (count($parts) > 0 && is_numeric($parts[0])) {
            $seconds = (int)$parts[0];
            
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);

            if ($days > 0) {
                return "{$days}d, {$hours}h, {$minutes}m";
            }
            if ($hours > 0) {
                return "{$hours}h, {$minutes}m";
            }
            return "{$minutes}m";
        }
        return 'Unknown';
    }
}
