<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ServiceStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $service;
    public $oldStatus;
    public $newStatus;

    public function __construct(Service $service, $oldStatus, $newStatus)
    {
        $this->service = $service;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($this->service->notify_email && $notifiable->email) {
            $channels[] = 'mail';
        }
        if ($this->service->notify_telegram && $notifiable->telegram_chat_id) {
            $channels[] = \NotificationChannels\Telegram\TelegramChannel::class;
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = strtoupper($this->newStatus);
        return (new MailMessage)
                    ->subject("Statify Alert: {$this->service->name} is {$statusText}")
                    ->line("The service '{$this->service->name}' on server '{$this->service->server->name}' changed status.")
                    ->line("Previous status: {$this->oldStatus}")
                    ->line("New status: {$this->newStatus}")
                    ->action('View Dashboard', url('/dashboard'))
                    ->line('Thank you for using Statify!');
    }

    public function toTelegram($notifiable)
    {
        $statusIcon = $this->newStatus === 'up' ? '✅' : '❌';
        $statusText = strtoupper($this->newStatus);
        
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->content("{$statusIcon} *Statify Alert* {$statusIcon}\n\n" .
                      "*Service:* {$this->service->name}\n" .
                      "*Server:* {$this->service->server->name}\n" .
                      "*Status:* {$statusText}\n" .
                      "*Previous:* {$this->oldStatus}");
    }
}
