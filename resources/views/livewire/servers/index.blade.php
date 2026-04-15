<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Servers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($showForm)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">{{ $isEditing ? 'Edit Server' : 'Add New Server' }}</h3>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hostname / Name</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IP Address</label>
                                <input type="text" wire:model="ip_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">OS (Optional)</label>
                                <input type="text" wire:model="os" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-3">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 font-bold">Save</button>
                            <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-bold">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Registered Servers</h3>
                    @if(!$showForm)
                        <button wire:click="create" class="px-4 py-2 bg-emerald-500 text-white rounded-md shadow-sm hover:bg-emerald-600 font-bold text-sm text-center align-middle inline-block">
                            + Add Server
                        </button>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">IP Address</th>
                                <th class="px-4 py-3">OS</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($servers as $server)
                            <tr class="text-gray-700 hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">{{ $server->name }}</td>
                                <td class="px-4 py-3">{{ $server->ip_address ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $server->os ?? '-' }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('services', $server->id) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold">Services</a>
                                    <button wire:click="edit({{ $server->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Edit</button>
                                    <button wire:click="delete({{ $server->id }})" class="text-red-600 hover:text-red-800 text-sm font-semibold" wire:confirm="Are you sure you want to delete this server? It will delete all attached services too!">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                            @if($servers->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">No servers currently tracked. Add one to begin.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
