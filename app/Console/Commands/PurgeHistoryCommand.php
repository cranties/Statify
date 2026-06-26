<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CheckHistory;
use Carbon\Carbon;

class PurgeHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statify:purge-history {--days= : Number of days of history to keep (minimum 7)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge check history records older than the specified number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days') ?: config('services.monitoring.retention_days', 30);

        if (!is_numeric($days) || $days < 7) {
            $this->error('Please specify a valid number of days (minimum 7 days to preserve dashboard trend charts).');
            return 1;
        }

        $this->info("Purging check history older than {$days} days...");

        $cutoff = Carbon::now()->subDays($days);
        $deletedCount = CheckHistory::where('created_at', '<', $cutoff)->delete();
        $deletedStatsCount = \App\Models\ServerStat::where('created_at', '<', $cutoff)->delete();

        $this->info("Successfully deleted {$deletedCount} old check history records and {$deletedStatsCount} old server performance records.");
        return 0;
    }
}
