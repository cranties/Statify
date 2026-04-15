<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $servers;
    public $serverId;
    public $name;
    public $ip_address;
    public $os;
    public $description;
    public $isEditing = false;
    public $showForm = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:45',
            'os' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->loadServers();
    }

    public function loadServers()
    {
        $this->servers = Server::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $server = Server::findOrFail($id);
        $this->serverId = $id;
        $this->name = $server->name;
        $this->ip_address = $server->ip_address;
        $this->os = $server->os;
        $this->description = $server->description;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $server = Server::findOrFail($this->serverId);
            $server->update($this->only(['name', 'ip_address', 'os', 'description']));
        } else {
            Server::create($this->only(['name', 'ip_address', 'os', 'description']));
        }

        $this->resetForm();
        $this->loadServers();
    }

    public function delete($id)
    {
        Server::findOrFail($id)->delete();
        $this->loadServers();
    }

    public function resetForm()
    {
        $this->reset(['name', 'ip_address', 'os', 'description', 'serverId']);
        $this->isEditing = false;
        $this->showForm = false;
        $this->resetValidation();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.servers.index');
    }
}
