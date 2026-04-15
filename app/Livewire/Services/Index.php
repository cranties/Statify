<?php

namespace App\Livewire\Services;

use App\Models\Server;
use App\Models\Service;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public Server $server;
    public $services;
    public $serverChartData;

    public $serviceId;
    public $name;
    public $type = 'ping';
    public $port;
    public $endpoint;
    public $check_interval_minutes = 3;
    public $failure_threshold = 2;
    public $success_threshold = 1;
    public $notify_telegram = true;
    public $notify_email = true;
    public $is_active = true;

    public $isEditing = false;
    public $showForm = false;

    public function mount(Server $server)
    {
        $this->server = $server;
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = clone $this->server->services()->orderBy('name')->get();
        $this->buildServerChartData();
    }

    public function buildServerChartData()
    {
        $labels = [];
        $data = [];
        $colors = [];
        
        foreach ($this->services as $service) {
            $labels[] = $service->name;
            
            $total = $service->checkHistories()->count();
            $up = $service->checkHistories()->where('status', 'up')->count();
            
            $percent = $total > 0 ? round(($up / $total) * 100, 2) : 0;
            $data[] = $percent;
            
            if ($percent >= 98) $colors[] = '#10B981'; // emerald-500
            elseif ($percent >= 90) $colors[] = '#F59E0B'; // amber-500
            elseif ($total == 0) $colors[] = '#9CA3AF'; // gray-400
            else $colors[] = '#EF4444'; // red-500
        }
        
        $this->serverChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Uptime %',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ]
            ]
        ];
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'port' => 'nullable|integer',
            'endpoint' => 'nullable|string|max:1024',
            'check_interval_minutes' => 'required|integer|min:1',
            'failure_threshold' => 'required|integer|min:1',
            'success_threshold' => 'required|integer|min:1',
            'notify_telegram' => 'boolean',
            'notify_email' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $this->serviceId = $id;
        $this->name = $service->name;
        $this->type = $service->type;
        $this->port = $service->port;
        $this->endpoint = $service->endpoint;
        $this->check_interval_minutes = $service->check_interval_minutes;
        $this->failure_threshold = $service->failure_threshold;
        $this->success_threshold = $service->success_threshold;
        $this->notify_telegram = $service->notify_telegram;
        $this->notify_email = $service->notify_email;
        $this->is_active = $service->is_active;
        
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = $this->only([
            'name', 'type', 'port', 'endpoint', 
            'check_interval_minutes', 'failure_threshold', 'success_threshold',
            'notify_telegram', 'notify_email', 'is_active'
        ]);

        if ($this->isEditing) {
            Service::where('id', $this->serviceId)->update($data);
        } else {
            $this->server->services()->create($data);
        }

        $this->resetForm();
        $this->loadServices();
    }

    public function delete($id)
    {
        Service::findOrFail($id)->delete();
        $this->loadServices();
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'type', 'port', 'endpoint', 'serviceId'
        ]);
        $this->check_interval_minutes = 3;
        $this->failure_threshold = 2;
        $this->success_threshold = 1;
        $this->notify_telegram = true;
        $this->notify_email = true;
        $this->is_active = true;
        
        $this->isEditing = false;
        $this->showForm = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.services.index')->layout('layouts.app');
    }
}
