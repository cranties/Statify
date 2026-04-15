<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Analytics: ') }} {{ $service->name }}
                </h2>
                <span class="text-sm text-gray-500">{{ $service->type }} target: {{ $service->endpoint ?: $service->port ?: 'Auto-Ping' }}</span>
            </div>
            <a href="{{ route('services', $service->server_id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">&larr; Back to Services</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Uptime % -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center">
                    <span class="text-gray-500 text-sm font-medium">Uptime Globale</span>
                    <span class="text-5xl font-black {{ $uptimePercentage >= 99 ? 'text-emerald-500' : ($uptimePercentage >= 90 ? 'text-amber-500' : 'text-red-500') }} drop-shadow-sm mt-2">
                        {{ $uptimePercentage }}%
                    </span>
                </div>
                
                <!-- Current Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center">
                    <span class="text-gray-500 text-sm font-medium">Stato Attuale</span>
                    <span class="text-4xl font-bold uppercase {{ $service->status === 'up' ? 'text-emerald-500' : ($service->status === 'down' ? 'text-red-500' : 'text-gray-400') }} mt-2">
                        {{ $service->status ?: 'PENDING' }}
                    </span>
                    @if($service->last_checked_at)
                    <span class="text-xs text-gray-400 mt-2">Ultimo controllo: {{ \Carbon\Carbon::parse($service->last_checked_at)->diffForHumans() }}</span>
                    @endif
                </div>

                <!-- Avg Latency -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center">
                    <span class="text-gray-500 text-sm font-medium">Latenza Media</span>
                    <span class="text-3xl font-bold text-indigo-500 mt-2">
                        {{ $averageLatency }} ms
                    </span>
                </div>
            </div>

            <!-- Deep Graph -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" x-data="{
                    init() {
                        new Chart($refs.latencyCanvas.getContext('2d'), {
                            type: 'line',
                            data: @js($latencyChartData),
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { 
                                    y: { beginAtZero: true, title: {display: true, text: 'Ms'} } 
                                },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }
                }">
                <h3 class="text-lg font-bold mb-4 text-gray-700">Latenza nel tempo (Ultimi esiti)</h3>
                <div class="w-full relative h-[300px]">
                    <canvas x-ref="latencyCanvas"></canvas>
                </div>
            </div>

            <!-- Historical Log Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-700">Storage Locale (Log controlli)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Esito</th>
                                <th class="px-4 py-3">Latenza</th>
                                <th class="px-4 py-3">Messaggio Tecnico</th>
                                <th class="px-4 py-3 text-right">Data/Ora</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($logs as $log)
                            <tr class="text-gray-700 hover:bg-gray-50 text-sm">
                                <td class="px-4 py-3">
                                    @if($log->status === 'up')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-green-100 text-green-700">UP</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-red-100 text-red-700">DOWN</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $log->response_time_ms ? $log->response_time_ms.' ms' : '-' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500 truncate max-w-xs">{{ $log->message ?: 'Nessun dettaglio extra.' }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endforeach
                            @if($logs->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">Nessun controllo registrato.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 border-t">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
