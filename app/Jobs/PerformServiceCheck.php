<?php

namespace App\Jobs;

use App\Models\Service;
use App\Models\CheckHistory;
use App\Services\Checkers\CheckerFactory;
use App\Notifications\ServiceStatusChanged;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class PerformServiceCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function handle(): void
    {
        $checker = CheckerFactory::make($this->service);
        $result = $checker->check();

        $this->service->last_checked_at = now();

        $history = new CheckHistory([
            'status' => $result['status'],
            'response_time_ms' => $result['response_time_ms'] ?? null,
            'message' => $result['message'] ?? null,
        ]);

        $this->service->checkHistories()->save($history);

        $oldStatus = $this->service->status;
        $newStatus = $oldStatus; // Assume no change initially

        if ($result['status'] === 'up') {
            $this->service->consecutive_successes++;
            $this->service->consecutive_failures = 0;

            if ($this->service->consecutive_successes >= $this->service->success_threshold && $oldStatus !== 'up') {
                $newStatus = 'up';
            }
        } else {
            $this->service->consecutive_failures++;
            $this->service->consecutive_successes = 0;

            if ($this->service->consecutive_failures >= $this->service->failure_threshold && $oldStatus !== 'down') {
                $newStatus = 'down';
            }
        }

        if ($newStatus !== $oldStatus) {
            $this->service->status = $newStatus;
            $this->service->last_status_change_at = now();
            
            // Notify all admins (in this case all registered users, as it's a private tool)
            $users = User::all();
            Notification::send($users, new ServiceStatusChanged($this->service, $oldStatus, $newStatus));
        }

        $this->service->save();
    }
}
