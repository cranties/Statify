<?php

namespace App\Livewire\Configuration;

use App\Models\NotificationTemplate;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TelegramSettings extends Component
{
    public $content;
    public $successMessage = '';

    public function mount()
    {
        $template = NotificationTemplate::getTemplate('telegram', [
            'content' => "%status_icon% *Statify Alert* %status_icon%\n\n*Service:* %service%\n*Server:* %server% (%server_ip%)\n*Status:* %status%\n*Previous:* %old_status%\n*Date:* %date%"
        ]);
        $this->content = $template->content;
    }

    public function save($content)
    {
        $this->content = $content;

        $this->validate([
            'content' => 'required|string',
        ]);

        $template = NotificationTemplate::getTemplate('telegram');
        $template->update([
            'content' => $this->content,
        ]);

        $this->successMessage = 'Telegram template updated successfully!';
        $this->dispatch('saved');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.configuration.telegram-settings');
    }
}
