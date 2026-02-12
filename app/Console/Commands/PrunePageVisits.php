<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PageVisit;
use Illuminate\Support\Carbon;

class PrunePageVisits extends Command
{
    protected $signature = 'visits:prune {days=90 : The number of days to keep}';
    protected $description = 'Prune old page visits';

    public function handle()
    {
        $days = $this->argument('days') ?? 90;
        $date = Carbon::today()->subDays($days);

        $this->info("Deleting visits older than: " . $date->toDateString());

        $count = PageVisit::where('visited_at', '<', $date)->delete();

        $this->info("Deleted {$count} old visits.");
    }
}
