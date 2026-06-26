<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Jobs\PerformServiceCheck;

class RunChecksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statify:run-checks {--force : Force the check on all services ignoring the timer (interval)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the checks of Statify services manually from the console.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Statify checks...');

        $query = Service::where('is_active', true);

        if (!$this->option('force')) {
            $now = now();
            $query->where(function($q) use ($now) {
                $q->whereNull('last_checked_at')
                ->orWhereRaw('DATE_ADD(last_checked_at, INTERVAL check_interval_minutes MINUTE) <= ?', [$now]);
            });
            $this->comment('Checking only services that are currently due...');
        } else {
            $this->comment('FORCE mode: Checking all active services regardless of the due date...');
        }

        $services = $query->get();

        if ($services->isEmpty()) {
            $this->info('No services in queue for the check.');
            return;
        }

        $this->withProgressBar($services, function ($service) {
            dispatch_sync(new PerformServiceCheck($service));
        });

        $this->newLine();
        $this->info('Checks completed on ' . $services->count() . ' services.');
    }
}
