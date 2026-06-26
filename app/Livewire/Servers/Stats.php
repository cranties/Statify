<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Stats extends Component
{
    public Server $server;
    public $chartData;
    public $latestStat;

    public function mount(Server $server)
    {
        $this->server = $server;
        $this->latestStat = $server->latestStat;
        $this->buildChartData();
    }

    public function buildChartData()
    {
        $startTrend = Carbon::now()->subDays(7)->startOfDay();
        $driver = DB::getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d %H:00:00', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";

        $hourlyData = $this->server->stats()
            ->where('created_at', '>=', $startTrend)
            ->select(
                DB::raw("{$hourExpression} as hour_key"),
                DB::raw("AVG(cpu_usage) as avg_cpu"),
                DB::raw("AVG(ram_usage) as avg_ram"),
                DB::raw("AVG(disk_usage) as avg_disk")
            )
            ->groupBy('hour_key')
            ->orderBy('hour_key')
            ->get();

        $labels = [];
        $cpu = [];
        $ram = [];
        $disk = [];

        foreach ($hourlyData as $data) {
            $time = Carbon::parse($data->hour_key);
            $labels[] = $time->format('D d/m H:i');
            $cpu[] = round($data->avg_cpu, 1);
            $ram[] = round($data->avg_ram, 1);
            $disk[] = round($data->avg_disk, 1);
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'CPU Usage (%)',
                    'data' => $cpu,
                    'borderColor' => '#3B82F6', // blue-500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Memory Usage (%)',
                    'data' => $ram,
                    'borderColor' => '#8B5CF6', // purple-500
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Disk Usage (%)',
                    'data' => $disk,
                    'borderColor' => '#10B981', // emerald-500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ]
            ]
        ];
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.servers.stats');
    }
}
