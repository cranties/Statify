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
        // --- Core KPIs ---
        $serversCount   = Server::count();
        $servicesCount  = Service::count();
        $upCount        = Service::where('status', 'up')->count();
        $downCount      = Service::where('status', 'down')->count();
        $pendingCount   = Service::where('status', 'pending')->count();
        $activeCount    = Service::where('is_active', true)->count();

        // Uptime percentage
        $uptimePercent = $servicesCount > 0
            ? round(($upCount / $servicesCount) * 100, 1)
            : 0;

        // Average response time (last 24h)
        $avgResponseTime = CheckHistory::where('created_at', '>=', Carbon::now()->subDay())
            ->whereNotNull('response_time_ms')
            ->avg('response_time_ms');
        $avgResponseTime = $avgResponseTime ? round($avgResponseTime, 1) : null;

        // Total checks in last 24h
        $checksLast24h = CheckHistory::where('created_at', '>=', Carbon::now()->subDay())->count();

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

        // Uptime trend last 24h (hourly buckets)
        $uptimeTrend = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour  = Carbon::now()->subHours($i);
            $label = $hour->format('H:i');
            $total = CheckHistory::whereBetween('created_at', [$hour->copy()->startOfHour(), $hour->copy()->endOfHour()])->count();
            $upH   = CheckHistory::whereBetween('created_at', [$hour->copy()->startOfHour(), $hour->copy()->endOfHour()])
                ->where('status', 'up')->count();
            $pct   = $total > 0 ? round(($upH / $total) * 100, 0) : null;
            $uptimeTrend[] = ['label' => $label, 'value' => $pct, 'total' => $total];
        }

        // Response time trend last 24h (hourly)
        $responseTrend = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour  = Carbon::now()->subHours($i);
            $label = $hour->format('H:i');
            $avg   = CheckHistory::whereBetween('created_at', [$hour->copy()->startOfHour(), $hour->copy()->endOfHour()])
                ->whereNotNull('response_time_ms')
                ->avg('response_time_ms');
            $responseTrend[] = ['label' => $label, 'value' => $avg ? round($avg, 1) : null];
        }

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

        // Checks success rate last 7 days (daily)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $day   = Carbon::now()->subDays($i);
            $label = $day->format('D d/m');
            $total = CheckHistory::whereDate('created_at', $day)->count();
            $upD   = CheckHistory::whereDate('created_at', $day)->where('status', 'up')->count();
            $downD = CheckHistory::whereDate('created_at', $day)->where('status', 'down')->count();
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

        return view('livewire.dashboard', compact(
            'serversCount', 'servicesCount', 'upCount', 'downCount', 'pendingCount',
            'activeCount', 'uptimePercent', 'avgResponseTime', 'checksLast24h',
            'servicesByType', 'serverStats', 'uptimeTrend', 'responseTrend',
            'recentIncidents', 'criticalServices', 'dailyStats', 'services'
        ));
    }
}
