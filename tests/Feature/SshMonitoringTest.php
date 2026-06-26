<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Server;
use App\Models\Service;
use App\Models\ServerStat;
use App\Services\Checkers\CheckerFactory;
use App\Services\Checkers\SshChecker;
use App\Services\Checkers\TcpHandshakeChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SshMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_resolves_ssh_checker_only_when_credentials_present()
    {
        $server = Server::create([
            'name' => 'Test Server',
            'ip_address' => '127.0.0.1',
        ]);

        // 1. Without credentials
        $serviceWithoutCreds = Service::create([
            'server_id' => $server->id,
            'name' => 'SSH Check No Creds',
            'type' => 'ssh',
            'port' => 22,
            'check_interval_minutes' => 3,
        ]);

        $checker1 = CheckerFactory::make($serviceWithoutCreds);
        $this->assertInstanceOf(TcpHandshakeChecker::class, $checker1);

        // 2. With credentials
        $serviceWithCreds = Service::create([
            'server_id' => $server->id,
            'name' => 'SSH Check With Creds',
            'type' => 'ssh',
            'port' => 22,
            'check_interval_minutes' => 3,
            'credentials' => [
                'username' => 'root',
                'password' => 'secret',
            ]
        ]);

        $checker2 = CheckerFactory::make($serviceWithCreds);
        $this->assertInstanceOf(SshChecker::class, $checker2);
    }

    public function test_ssh_checker_successfully_saves_stats_and_returns_up()
    {
        $server = Server::create([
            'name' => 'Test Server',
            'ip_address' => '127.0.0.1',
        ]);

        $service = Service::create([
            'server_id' => $server->id,
            'name' => 'SSH Check',
            'type' => 'ssh',
            'port' => 22,
            'check_interval_minutes' => 3,
            'credentials' => [
                'username' => 'root',
                'password' => 'secret',
            ]
        ]);

        // Mock phpseclib3\Net\SSH2 using overload
        $mockSsh = Mockery::mock('overload:phpseclib3\Net\SSH2');
        $mockSsh->shouldReceive('login')
            ->with('root', 'secret')
            ->andReturn(true);

        // Mock free -m
        $ramOutput = "               total        used        free      shared  buff/cache   available\n" .
                     "Mem:            8000        2000        1000         100        5000        6000\n" .
                     "Swap:           2000           0        2000";
        $mockSsh->shouldReceive('exec')
            ->with('free -m')
            ->andReturn($ramOutput);

        // Mock df -m /
        $diskOutput = "Filesystem   1M-blocks   Used Available Capacity Mounted on\n" .
                      "/dev/sda1       100000  40000     60000      40% /";
        $mockSsh->shouldReceive('exec')
            ->with('df -m /')
            ->andReturn($diskOutput);

        // Mock top -bn1
        $cpuOutput = "%Cpu(s):  5.0 us,  2.0 sy,  0.0 ni, 90.0 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st";
        $mockSsh->shouldReceive('exec')
            ->with('top -bn1')
            ->andReturn($cpuOutput);

        // Mock cat /proc/uptime
        $uptimeOutput = "1209600.00 2419200.00"; // 14 days
        $mockSsh->shouldReceive('exec')
            ->with('cat /proc/uptime')
            ->andReturn($uptimeOutput);

        $checker = new SshChecker($service);
        $result = $checker->check();

        $this->assertEquals('up', $result['status']);
        $this->assertStringContainsString('SSH Connected', $result['message']);

        // Verify stats are created
        $this->assertDatabaseHas('server_stats', [
            'server_id' => $server->id,
            'cpu_usage' => 10.0, // 100 - 90
            'ram_usage' => 25.0, // (8000 - 6000) / 8000 * 100
            'ram_total' => 7.81, // 8000 / 1024
            'ram_used' => 1.95,  // 2000 / 1024
            'disk_usage' => 40.0,
            'disk_total' => 97.66, // 100000 / 1024
            'disk_used' => 39.06,  // 40000 / 1024
            'uptime' => '14d, 0h, 0m',
            'health_status' => 'healthy',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
