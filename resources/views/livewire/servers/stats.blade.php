<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Performance Analytics for ') }} {{ $server->name }}
                </h2>
                <span class="text-sm text-gray-500">{{ $server->ip_address }}</span>
            </div>
            <a href="{{ route('servers') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">&larr;
                Back to Servers</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- KPIs row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Health Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Health & Uptime</span>
                    <div class="mt-4 flex items-center gap-3">
                        @if (!$latestStat)
                            <span class="w-4 h-4 rounded-full bg-gray-400"></span>
                            <span class="font-bold text-gray-600">NO DATA</span>
                        @elseif($latestStat->health_status === 'healthy')
                            <span class="w-4 h-4 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="font-bold text-green-600 uppercase">Healthy</span>
                        @elseif($latestStat->health_status === 'warning')
                            <span class="w-4 h-4 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="font-bold text-amber-600 uppercase">Warning</span>
                        @else
                            <span class="w-4 h-4 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="font-bold text-red-600 uppercase">Critical</span>
                        @endif
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50 text-xs text-gray-500">
                        <span class="font-bold text-gray-700">Uptime:</span> {{ $latestStat->uptime ?? 'Unknown' }}
                    </div>
                </div>

                <!-- CPU Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Current CPU Load</span>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-3xl font-black text-gray-800">{{ $latestStat->cpu_usage ?? 0 }}</span>
                        <span class="text-lg font-bold text-gray-400">%</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $latestStat->cpu_usage ?? 0 }}%">
                        </div>
                    </div>
                </div>

                <!-- Memory Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Memory Utilization</span>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-3xl font-black text-gray-800">{{ $latestStat->ram_usage ?? 0 }}</span>
                        <span class="text-lg font-bold text-gray-400">%</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        {{ $latestStat->ram_used ?? 0 }} GB / {{ $latestStat->ram_total ?? 0 }} GB
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $latestStat->ram_usage ?? 0 }}%">
                        </div>
                    </div>
                </div>

                <!-- Disk Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Storage space</span>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-3xl font-black text-gray-800">{{ $latestStat->disk_usage ?? 0 }}</span>
                        <span class="text-lg font-bold text-gray-400">%</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        {{ $latestStat->disk_used ?? 0 }} GB / {{ $latestStat->disk_total ?? 0 }} GB
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full"
                            style="width: {{ $latestStat->disk_usage ?? 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Historical Chart Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{
                init() {
                    new Chart($refs.canvas.getContext('2d'), {
                        type: 'line',
                        data: @js($chartData),
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: { callback: value => value + '%' }
                                }
                            },
                            plugins: {
                                legend: { position: 'top' }
                            }
                        }
                    });
                }
            }">
                <h3 class="text-lg font-bold text-gray-700 mb-6">7-Day Performance Trends</h3>
                <div class="w-full relative h-[400px]" style="height: 400px;">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
