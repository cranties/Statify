<?php

namespace App\Livewire;

use App\Models\Server;
use App\Models\Service;
use App\Models\CheckHistory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $yesterday = Carbon::now()->subDay();

        // --- Core KPIs (Limited to last 24h) ---
        $servicesStats = Service::where('last_checked_at', '>=', $yesterday)
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as up_count"),
                DB::raw("SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as down_count"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
                DB::raw("SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count")
            )
            ->first();

        $servicesCount = $servicesStats->total ?? 0;
        $upCount       = $servicesStats->up_count ?? 0;
        $downCount     = $servicesStats->down_count ?? 0;
        $pendingCount  = $servicesStats->pending_count ?? 0;
        $activeCount   = $servicesStats->active_count ?? 0;

        $serversCount = Server::whereHas('services', fn($q) => $q->where('last_checked_at', '>=', $yesterday))->count();

        // Uptime percentage
        $uptimePercent = $servicesCount > 0
            ? round(($upCount / $servicesCount) * 100, 1)
            : 0;

        // Uptime and Response trends (Optimized to 1 query)
        $startTrend = Carbon::now()->subHours(23)->startOfHour();
        $driver = DB::getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d %H:00:00', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";

        $hourlyData = CheckHistory::where('created_at', '>=', $startTrend)
            ->select(
                DB::raw("{$hourExpression} as hour_key"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as up_count"),
                DB::raw("AVG(response_time_ms) as avg_response_time")
            )
            ->groupBy('hour_key')
            ->orderBy('hour_key')
            ->get()
            ->keyBy('hour_key');

        $uptimeTrend = [];
        $responseTrend = [];
        $now = Carbon::now();

        for ($i = 23; $i >= 0; $i--) {
            $hour = $now->copy()->subHours($i);
            $label = $hour->format('H:i');
            $hourKey = $hour->format('Y-m-d H:00:00');

            $data = $hourlyData->get($hourKey);

            if ($data) {
                $total = $data->total;
                $upH = $data->up_count;
                $pct = $total > 0 ? round(($upH / $total) * 100, 0) : null;
                $avg = $data->avg_response_time;
            } else {
                $total = 0;
                $pct = null;
                $avg = null;
            }

            $uptimeTrend[] = ['label' => $label, 'value' => $pct, 'total' => $total];
            $responseTrend[] = ['label' => $label, 'value' => $avg ? round($avg, 1) : null];
        }

        // Total checks in last 24h (summed from hourly trend)
        $checksLast24h = $hourlyData->sum('total');

        // Average response time (last 24h)
        $avgResponseTime = CheckHistory::where('created_at', '>=', $yesterday)
            ->whereNotNull('response_time_ms')
            ->avg('response_time_ms');
        $avgResponseTime = $avgResponseTime ? round($avgResponseTime, 1) : null;

        // Services by type (for donut chart)
        $servicesByType = Service::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Services by status per server (for bar chart)
        $serverStats = Server::withCount([
            'services as up_count'      => fn($q) => $q->where('status', 'up'),
            'services as down_count'    => fn($q) => $q->where('status', 'down'),
            'services as pending_count' => fn($q) => $q->where('status', 'pending'),
        ])->get();

        // Recent incidents (last 10 downs)
        $recentIncidents = CheckHistory::with('service.server')
            ->where('status', 'down')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Services sorted by most failures
        $criticalServices = Service::with('server')
            ->orderByDesc('consecutive_failures')
            ->where('consecutive_failures', '>', 0)
            ->limit(5)
            ->get();

        // Checks success rate last 7 days (Optimized to 1 query)
        $startDaily = Carbon::now()->subDays(6)->startOfDay();
        $dateExpression = $driver === 'sqlite'
            ? "date(created_at)"
            : "DATE(created_at)";

        $dailyData = CheckHistory::where('created_at', '>=', $startDaily)
            ->select(
                DB::raw("{$dateExpression} as date_key"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as up_count"),
                DB::raw("SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as down_count")
            )
            ->groupBy('date_key')
            ->get()
            ->keyBy('date_key');

        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $day   = Carbon::now()->subDays($i);
            $label = $day->format('D d/m');
            $dateKey = $day->format('Y-m-d');

            $data = $dailyData->get($dateKey);

            if ($data) {
                $total = $data->total;
                $upD   = $data->up_count;
                $downD = $data->down_count;
            } else {
                $total = 0;
                $upD   = 0;
                $downD = 0;
            }

            $dailyStats[] = [
                'label' => $label,
                'up'    => $upD,
                'down'  => $downD,
                'total' => $total,
                'pct'   => $total > 0 ? round(($upD / $total) * 100, 1) : null,
            ];
        }

        // Full services table
        $services = Service::with('server')
            ->orderByRaw("CASE WHEN status = 'down' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
            ->get();

        // --- SSH Server Performance Trends (Last 24h) ---
        $perfHourExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d %H:00:00', server_stats.created_at)"
            : "DATE_FORMAT(server_stats.created_at, '%Y-%m-%d %H:00:00')";

        $startPerformance = Carbon::now()->subHours(23)->startOfHour();
        $performanceData = DB::table('server_stats')
            ->join('servers', 'server_stats.server_id', '=', 'servers.id')
            ->where('server_stats.created_at', '>=', $startPerformance)
            ->select(
                'servers.name as server_name',
                'servers.id as server_id',
                DB::raw("{$perfHourExpression} as hour_key"),
                DB::raw("AVG(cpu_usage) as avg_cpu"),
                DB::raw("AVG(ram_usage) as avg_ram"),
                DB::raw("AVG(disk_usage) as avg_disk")
            )
            ->groupBy('server_id', 'server_name', 'hour_key')
            ->orderBy('hour_key')
            ->get();

        $sshServers = Server::whereHas('stats')->pluck('name', 'id')->toArray();
        
        $perfLabels = [];
        $cpuDatasets = [];
        $ramDatasets = [];
        $diskDatasets = [];
        
        $colors = [
            '#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', 
            '#EC4899', '#06B6D4', '#14B8A6', '#F97316', '#64748B'
        ];
        $colorIdx = 0;
        
        foreach ($sshServers as $id => $name) {
            $color = $colors[$colorIdx % count($colors)];
            $colorIdx++;
            
            $cpuDatasets[$id] = [
                'label' => $name,
                'data' => array_fill(0, 24, null),
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
                'fill' => false,
            ];
            $ramDatasets[$id] = [
                'label' => $name,
                'data' => array_fill(0, 24, null),
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
                'fill' => false,
            ];
            $diskDatasets[$id] = [
                'label' => $name,
                'data' => array_fill(0, 24, null),
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'tension' => 0.3,
                'fill' => false,
            ];
        }

        $slots = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = Carbon::now()->subHours($i);
            $perfLabels[] = $hour->format('H:i');
            $slots[$hour->format('Y-m-d H:00:00')] = 23 - $i;
        }

        foreach ($performanceData as $row) {
            $hourKey = Carbon::parse($row->hour_key)->format('Y-m-d H:00:00');
            if (isset($slots[$hourKey])) {
                $idx = $slots[$hourKey];
                $sid = $row->server_id;
                if (isset($cpuDatasets[$sid])) {
                    $cpuDatasets[$sid]['data'][$idx] = round($row->avg_cpu, 1);
                    $ramDatasets[$sid]['data'][$idx] = round($row->avg_ram, 1);
                    $diskDatasets[$sid]['data'][$idx] = round($row->avg_disk, 1);
                }
            }
        }

        $cpuChartData = [
            'labels' => $perfLabels,
            'datasets' => array_values($cpuDatasets),
        ];
        $ramChartData = [
            'labels' => $perfLabels,
            'datasets' => array_values($ramDatasets),
        ];
        $diskChartData = [
            'labels' => $perfLabels,
            'datasets' => array_values($diskDatasets),
        ];

        return view('livewire.dashboard', compact(
            'serversCount', 'servicesCount', 'upCount', 'downCount', 'pendingCount',
            'activeCount', 'uptimePercent', 'avgResponseTime', 'checksLast24h',
            'servicesByType', 'serverStats', 'uptimeTrend', 'responseTrend',
            'recentIncidents', 'criticalServices', 'dailyStats', 'services',
            'cpuChartData', 'ramChartData', 'diskChartData'
        ));
    }
}
