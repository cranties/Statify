<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Infrastructure Dashboard</h2>
                    <p class="text-xs text-gray-400">Real-time server &amp; service monitoring</p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block"></span>
                Live · Updated <span id="last-update" class="ml-1 font-semibold text-gray-700">just now</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-0">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- ===================== ROW 1 — PRIMARY KPIs ===================== --}}
            <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">

                {{-- Total Servers --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Servers</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ $serversCount }}</div>
                    <div class="text-xs text-slate-400 mt-1">Hosts monitored</div>
                </div>

                {{-- Total Services --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-indigo-200">Services</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ $servicesCount }}</div>
                    <div class="text-xs text-indigo-200 mt-1">{{ $activeCount }} active</div>
                </div>

                {{-- Services UP --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-100">UP</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-emerald-100" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ $upCount }}</div>
                    <div class="text-xs text-emerald-100 mt-1">Operational</div>
                </div>

                {{-- Services DOWN --}}
                <div
                    class="xl:col-span-1 {{ $downCount > 0 ? 'bg-gradient-to-br from-red-500 to-rose-600' : 'bg-gradient-to-br from-red-400/60 to-rose-500/60' }} rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-red-100">DOWN</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition {{ $downCount > 0 ? 'animate-pulse' : '' }}">
                            <svg class="w-4 h-4 text-red-100" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ $downCount }}</div>
                    <div class="text-xs text-red-100 mt-1">{{ $downCount > 0 ? 'Alerts active!' : 'All clear' }}</div>
                </div>

                {{-- Uptime % --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-cyan-500 to-sky-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-cyan-100">Uptime</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-cyan-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ $uptimePercent }}<span
                            class="text-xl font-bold text-cyan-200">%</span></div>
                    <div class="text-xs text-cyan-100 mt-1">Current ratio</div>
                </div>

                {{-- Avg Response Time --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-amber-100">Avg Resp.</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">
                        @if ($avgResponseTime)
                            {{ $avgResponseTime > 1000 ? round($avgResponseTime / 1000, 2) : $avgResponseTime }}
                            <span
                                class="text-xl font-bold text-amber-200">{{ $avgResponseTime > 1000 ? 's' : 'ms' }}</span>
                        @else
                            <span class="text-2xl font-bold text-amber-200">—</span>
                        @endif
                    </div>
                    <div class="text-xs text-amber-100 mt-1">Last 24h avg</div>
                </div>

                {{-- Checks 24h --}}
                <div
                    class="xl:col-span-1 bg-gradient-to-br from-fuchsia-500 to-pink-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-fuchsia-100">Checks</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition">
                            <svg class="w-4 h-4 text-fuchsia-100" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-black">{{ number_format($checksLast24h) }}</div>
                    <div class="text-xs text-fuchsia-100 mt-1">Last 24h</div>
                </div>
            </div>

            {{-- ===================== ROW 2 — UPTIME PROGRESS BAR + PENDING --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Uptime Gauge Bar --}}
                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700">System Health Overview</h3>
                        <span
                            class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-md font-mono">{{ now()->format('H:i:s') }}
                            UTC+2</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Services UP</span><span
                                    class="font-semibold text-emerald-600">{{ $upCount }} /
                                    {{ $servicesCount }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-3 rounded-full transition-all duration-700"
                                    style="width: {{ $servicesCount > 0 ? ($upCount / $servicesCount) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Services DOWN</span><span class="font-semibold text-red-500">{{ $downCount }}
                                    / {{ $servicesCount }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-red-400 to-rose-500 h-3 rounded-full transition-all duration-700"
                                    style="width: {{ $servicesCount > 0 ? ($downCount / $servicesCount) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Pending / Unknown</span><span
                                    class="font-semibold text-gray-500">{{ $pendingCount }} /
                                    {{ $servicesCount }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-gray-300 to-gray-400 h-3 rounded-full transition-all duration-700"
                                    style="width: {{ $servicesCount > 0 ? ($pendingCount / $servicesCount) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Active (monitored)</span><span
                                    class="font-semibold text-indigo-600">{{ $activeCount }} /
                                    {{ $servicesCount }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-indigo-400 to-violet-500 h-3 rounded-full transition-all duration-700"
                                    style="width: {{ $servicesCount > 0 ? ($activeCount / $servicesCount) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service Types Donut --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-700 mb-4">Services by Type</h3>
                    <div class="relative flex items-center justify-center h-32">
                        <canvas id="donutChart" width="120" height="120"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="text-center">
                                <div class="text-2xl font-black text-gray-800">{{ $servicesCount }}</div>
                                <div class="text-xs text-gray-400">total</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1.5">
                        @php
                            $typeColors = [
                                'http' => '#6366f1',
                                'tcp' => '#06b6d4',
                                'ping' => '#10b981',
                                'mysql' => '#f59e0b',
                                'pgsql' => '#8b5cf6',
                                'redis' => '#ef4444',
                            ];
                            $typeIdx = 0;
                            $palette = ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#f472b6'];
                        @endphp
                        @foreach ($servicesByType as $type => $count)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full inline-block"
                                        style="background:{{ $palette[$typeIdx % count($palette)] }}"></span>
                                    <span class="text-gray-600 uppercase font-medium">{{ $type }}</span>
                                </div>
                                <span class="font-bold text-gray-800">{{ $count }}</span>
                            </div>
                            @php $typeIdx++ @endphp
                        @endforeach
                        @if (empty($servicesByType))
                            <div class="text-xs text-gray-400 text-center py-2">No services yet</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===================== ROW 3 — CHARTS ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Uptime Trend 24h --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-700">Uptime Trend</h3>
                            <p class="text-xs text-gray-400">Last 24 hours — hourly uptime %</p>
                        </div>
                        <span
                            class="text-xs bg-emerald-50 text-emerald-600 px-2 py-1 rounded-lg font-semibold border border-emerald-100">24h</span>
                    </div>
                    <canvas id="uptimeChart" height="120"></canvas>
                </div>

                {{-- Response Time Trend 24h --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-700">Response Time</h3>
                            <p class="text-xs text-gray-400">Last 24 hours — avg ms per hour</p>
                        </div>
                        <span
                            class="text-xs bg-amber-50 text-amber-600 px-2 py-1 rounded-lg font-semibold border border-amber-100">ms</span>
                    </div>
                    <canvas id="responseChart" height="120"></canvas>
                </div>
            </div>

            {{-- ===================== ROW 4 — DAILY STATS + SERVER BAR ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- Daily checks bar (7d) --}}
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-700">Daily Check Results</h3>
                            <p class="text-xs text-gray-400">Last 7 days — UP vs DOWN checks</p>
                        </div>
                        <span
                            class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-lg font-semibold border border-indigo-100">7d</span>
                    </div>
                    <canvas id="dailyChart" height="110"></canvas>
                </div>

                {{-- Services per Server --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-700">Services per Server</h3>
                            <p class="text-xs text-gray-400">UP / DOWN distribution</p>
                        </div>
                    </div>
                    <canvas id="serverBarChart" height="150"></canvas>
                </div>
            </div>

            {{-- ===================== ROW 5 — CRITICAL + INCIDENTS ===================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Critical Services --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></div>
                        <h3 class="font-bold text-gray-700">Critical Services</h3>
                        <span class="ml-auto text-xs text-gray-400">Most consecutive failures</span>
                    </div>
                    @if ($criticalServices->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mb-2 text-emerald-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold text-emerald-500">All services healthy!</span>
                            <span class="text-xs mt-1">No consecutive failures detected.</span>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($criticalServices as $svc)
                                <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-100 rounded-xl">
                                    <div
                                        class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 text-sm truncate">{{ $svc->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $svc->server->name }} · <span
                                                class="uppercase font-medium text-indigo-500">{{ $svc->type }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-xl font-black text-red-500">{{ $svc->consecutive_failures }}
                                        </div>
                                        <div class="text-xs text-gray-400">failures</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Recent Incidents --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-500"></div>
                        <h3 class="font-bold text-gray-700">Recent Incidents</h3>
                        <span class="ml-auto text-xs text-gray-400">Last 10 DOWN events</span>
                    </div>
                    @if ($recentIncidents->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mb-2 text-emerald-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm font-semibold text-emerald-500">No incidents recorded!</span>
                            <span class="text-xs mt-1">Everything is running smoothly.</span>
                        </div>
                    @else
                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                            @foreach ($recentIncidents as $incident)
                                <div
                                    class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                                    <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0 animate-pulse"></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-800 truncate">
                                            {{ optional($incident->service)->name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ optional(optional($incident->service)->server)->name ?? '—' }}
                                            @if ($incident->message)
                                                · <span
                                                    class="text-red-400">{{ Str::limit($incident->message, 40) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400 flex-shrink-0">
                                        {{ $incident->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===================== ROW 6 — FULL SERVICES TABLE ===================== --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Monitored Services</h3>
                        <p class="text-xs text-gray-400 mt-0.5">All services sorted by criticality</p>
                    </div>
                    <a href="{{ route('servers') }}"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1 transition">
                        Manage
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Server</th>
                                <th class="px-6 py-3">Service</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Port</th>
                                <th class="px-6 py-3">Interval</th>
                                <th class="px-6 py-3">Failures</th>
                                <th class="px-6 py-3">Last Check</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($services as $service)
                                <tr class="hover:bg-slate-50/70 transition group">
                                    <td class="px-6 py-3.5">
                                        @if ($service->status === 'up')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> UP
                                            </span>
                                        @elseif($service->status === 'down')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> DOWN
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> PENDING
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-7 h-7 bg-slate-100 rounded-md flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-semibold text-sm text-gray-800">{{ $service->server->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 text-sm text-gray-700 font-medium">{{ $service->name }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span
                                            class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $service->type }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-sm text-gray-500 font-mono">
                                        {{ $service->port ?? '—' }}</td>
                                    <td class="px-6 py-3.5 text-sm text-gray-500">
                                        {{ $service->check_interval_minutes }}m</td>
                                    <td class="px-6 py-3.5">
                                        @if ($service->consecutive_failures > 0)
                                            <span
                                                class="text-sm font-bold text-red-500">{{ $service->consecutive_failures }}</span>
                                        @else
                                            <span class="text-sm text-gray-300">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-xs text-gray-400">
                                        {{ $service->last_checked_at?->diffForHumans() ?? 'Never' }}</td>
                                </tr>
                            @endforeach
                            @if ($services->isEmpty())
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic">No services
                                        configured yet.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===================== CHART.JS SCRIPTS ===================== --}}
@push('scripts')
    <script>
        // Shared defaults
        Chart.defaults.font.family = "'Inter', 'system-ui', sans-serif";
        Chart.defaults.color = '#6b7280';

        const tooltipStyle = {
            backgroundColor: 'rgba(17,24,39,0.92)',
            titleColor: '#f9fafb',
            bodyColor: '#d1d5db',
            borderColor: '#374151',
            borderWidth: 1,
            padding: 10,
            cornerRadius: 8,
            displayColors: true,
        };

        // ---- DONUT: Services by type ----
        const donutLabels = @json(array_keys($servicesByType));
        const donutData = @json(array_values($servicesByType));
        const palette = ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#f472b6'];

        if (document.getElementById('donutChart') && donutLabels.length) {
            new Chart(document.getElementById('donutChart'), {
                type: 'doughnut',
                data: {
                    labels: donutLabels,
                    datasets: [{
                        data: donutData,
                        backgroundColor: palette.slice(0, donutLabels.length),
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            ...tooltipStyle,
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                            }
                        }
                    },
                    animation: {
                        animateScale: true
                    }
                }
            });
        }

        // ---- UPTIME TREND 24h ----
        const uptimeLabels = @json(array_column($uptimeTrend, 'label'));
        const uptimeValues = @json(array_column($uptimeTrend, 'value'));

        if (document.getElementById('uptimeChart')) {
            new Chart(document.getElementById('uptimeChart'), {
                type: 'line',
                data: {
                    labels: uptimeLabels,
                    datasets: [{
                        label: 'Uptime %',
                        data: uptimeValues,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.08)',
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        tension: 0.4,
                        fill: true,
                        spanGaps: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                callback: v => v + '%',
                                maxTicksLimit: 5,
                                font: {
                                    size: 10
                                }
                            },
                            border: {
                                dash: [4, 4]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            ...tooltipStyle,
                            callbacks: {
                                label: ctx => ` Uptime: ${ctx.parsed.y !== null ? ctx.parsed.y + '%' : 'No data'}`
                            }
                        }
                    },
                    animation: {
                        duration: 800
                    }
                }
            });
        }

        // ---- RESPONSE TIME TREND 24h ----
        const respLabels = @json(array_column($responseTrend, 'label'));
        const respValues = @json(array_column($responseTrend, 'value'));

        if (document.getElementById('responseChart')) {
            new Chart(document.getElementById('responseChart'), {
                type: 'line',
                data: {
                    labels: respLabels,
                    datasets: [{
                        label: 'Avg Response (ms)',
                        data: respValues,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.08)',
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f59e0b',
                        tension: 0.4,
                        fill: true,
                        spanGaps: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: 0,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                callback: v => v + 'ms',
                                maxTicksLimit: 5,
                                font: {
                                    size: 10
                                }
                            },
                            border: {
                                dash: [4, 4]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            ...tooltipStyle,
                            callbacks: {
                                label: ctx => ` Avg: ${ctx.parsed.y !== null ? ctx.parsed.y + ' ms' : 'No data'}`
                            }
                        }
                    },
                    animation: {
                        duration: 800
                    }
                }
            });
        }

        // ---- DAILY STATS 7d ----
        const dailyLabels = @json(array_column($dailyStats, 'label'));
        const dailyUp = @json(array_column($dailyStats, 'up'));
        const dailyDown = @json(array_column($dailyStats, 'down'));

        if (document.getElementById('dailyChart')) {
            new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                            label: 'UP',
                            data: dailyUp,
                            backgroundColor: 'rgba(16,185,129,0.8)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'DOWN',
                            data: dailyDown,
                            backgroundColor: 'rgba(239,68,68,0.75)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        y: {
                            stacked: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                maxTicksLimit: 5,
                                font: {
                                    size: 10
                                }
                            },
                            border: {
                                dash: [4, 4]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: tooltipStyle
                    },
                    animation: {
                        duration: 800
                    }
                }
            });
        }

        // ---- SERVER BAR ----
        const srvLabels = @json($serverStats->pluck('name')->toArray());
        const srvUp = @json($serverStats->pluck('up_count')->toArray());
        const srvDown = @json($serverStats->pluck('down_count')->toArray());
        const srvPending = @json($serverStats->pluck('pending_count')->toArray());

        if (document.getElementById('serverBarChart')) {
            new Chart(document.getElementById('serverBarChart'), {
                type: 'bar',
                data: {
                    labels: srvLabels.length ? srvLabels : ['No servers'],
                    datasets: [{
                            label: 'UP',
                            data: srvUp,
                            backgroundColor: 'rgba(16,185,129,0.8)',
                            borderRadius: 4
                        },
                        {
                            label: 'DOWN',
                            data: srvDown,
                            backgroundColor: 'rgba(239,68,68,0.75)',
                            borderRadius: 4
                        },
                        {
                            label: 'Pending',
                            data: srvPending,
                            backgroundColor: 'rgba(156,163,175,0.6)',
                            borderRadius: 4
                        },
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            },
                            border: {
                                dash: [4, 4]
                            }
                        },
                        y: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: tooltipStyle
                    },
                    animation: {
                        duration: 800
                    }
                }
            });
        }

        // Live clock update
        function updateTime() {
            const el = document.getElementById('last-update');
            if (el) el.textContent = new Date().toLocaleTimeString('it-IT');
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
@endpush
