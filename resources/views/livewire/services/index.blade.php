<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Services for ') }} {{ $server->name }}
                </h2>
                <span class="text-sm text-gray-500">{{ $server->ip_address }}</span>
            </div>
            <a href="{{ route('servers') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">&larr; Back to Servers</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Server Analytics Chart -->
            @if(count($services) > 0 && !$showForm)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6" x-data="{
                    init() {
                        new Chart($refs.canvas.getContext('2d'), {
                            type: 'bar',
                            data: @js($serverChartData),
                            options: {
                                scales: { y: { beginAtZero: true, max: 100, title: {display: true, text: 'Uptime %'} } },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }
                }">
                <h3 class="text-lg font-bold mb-4 text-gray-700">Combined Uptime Integrity</h3>
                <div class="w-full relative h-[250px]">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
            @endif
            
            @if($showForm)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'Edit Service' : 'Add New Service' }}</h3>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Basic details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Service Name</label>
                                    <input type="text" wire:model="name" placeholder="e.g. Primary Web App" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check Type</label>
                                    <select wire:model.live="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="ping">Ping (ICMP)</option>
                                        <option value="http">HTTP/HTTPS</option>
                                        <option value="mysql">MySQL</option>
                                        <option value="postgres">PostgreSQL</option>
                                        <option value="redis">Redis</option>
                                        <option value="ssh">SSH</option>
                                        <option value="dns">DNS</option>
                                        <option value="tcp">Custom TCP Port</option>
                                    </select>
                                </div>

                                @if(in_array($type, ['http', 'https', 'keyword']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Endpoint URL</label>
                                    <input type="text" wire:model="endpoint" placeholder="https://example.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                </div>
                                @endif

                                @if(in_array($type, ['mysql', 'postgres', 'redis', 'ssh', 'tcp']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Port</label>
                                    <input type="number" wire:model="port" placeholder="e.g. 3306" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">Active (Currently Checking)</label>
                                </div>
                            </div>

                            <!-- Thresholds and Alarms -->
                            <div class="space-y-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                                <h4 class="font-semibold text-gray-700 mb-2">Check Engine Config</h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check Interval (Minutes)</label>
                                    <input type="number" wire:model="check_interval_minutes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Schedules a check every X minutes.</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fails before DOWN</label>
                                        <input type="number" wire:model="failure_threshold" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Successes before UP</label>
                                        <input type="number" wire:model="success_threshold" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                
                                <h4 class="font-semibold text-gray-700 mt-6 mb-2">Notifications</h4>
                                <div class="flex space-x-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="notify_telegram" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Telegram</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="notify_email" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Email</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3 border-t pt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 font-bold">Save Configuration</button>
                            <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-bold">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Services</h3>
                    @if(!$showForm)
                        <button wire:click="create" class="px-4 py-2 bg-emerald-500 text-white rounded-md shadow-sm hover:bg-emerald-600 font-bold text-sm text-center align-middle inline-block">
                            + Add Service
                        </button>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Target</th>
                                <th class="px-4 py-3">Interval</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($services as $service)
                            <tr class="text-gray-700 hover:bg-gray-50 {{ !$service->is_active ? 'opacity-50' : '' }}">
                                <td class="px-4 py-3">
                                    @if(!$service->is_active)
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-gray-200 text-gray-600">INACTIVE</span>
                                    @elseif($service->status === 'up')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-green-100 text-green-700">UP</span>
                                    @elseif($service->status === 'down')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-red-100 text-red-700">DOWN</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold leading-sm bg-gray-100 text-gray-700">PENDING</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold">{{ $service->name }}</td>
                                <td class="px-4 py-3 text-sm font-bold text-indigo-500 uppercase">{{ $service->type }}</td>
                                <td class="px-4 py-3 text-sm">{{ $service->endpoint ?: $service->port ?: 'IP Ping' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $service->check_interval_minutes }}m ({{ $service->failure_threshold }} fails)</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('services.show', $service->id) }}" class="text-purple-600 hover:text-purple-800 text-sm font-semibold">📈 Stats</a>
                                    <button wire:click="edit({{ $service->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Edit</button>
                                    <button wire:click="delete({{ $service->id }})" class="text-red-600 hover:text-red-800 text-sm font-semibold" wire:confirm="Are you sure you want to delete this service?">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                            @if($services->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500 italic">No services configured for this server.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
