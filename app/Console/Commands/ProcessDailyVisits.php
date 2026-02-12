<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PageVisit;
use App\Models\VisitSummary;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessDailyVisits extends Command
{
    protected $signature = 'visits:process {date? : The date to process (YYYY-MM-DD)}';
    protected $description = 'Aggregate page visits into daily summary';

    public function handle()
    {
        $date = $this->argument('date') 
            ? Carbon::parse($this->argument('date')) 
            : Carbon::yesterday(); // افتراضياً يعالج يوم أمس

        $this->info("Processing visits for: " . $date->toDateString());

        // 1. حساب الإحصائيات الأساسية
        $visits = PageVisit::whereDate('visited_at', $date);
        
        $totalVisits = $visits->count();
        if ($totalVisits === 0) {
            $this->warn("No visits found for this date.");
            return;
        }

        $uniqueVisitors = $visits->distinct('ip_address')->count('ip_address');
        $totalSessions = $visits->distinct('session_id')->count('session_id');

        // 2. توزيع الأجهزة
        $deviceStats = $visits->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        // 3. توزيع المتصفحات (Top 5)
        $browserStats = $visits->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'browser')
            ->toArray();

        // 4. أكثر الصفحات زيارة (Top 10)
        $topPages = $visits->select('url', 'page_title', DB::raw('count(*) as count'))
            ->groupBy('url', 'page_title')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($row) => [
                'url' => $row->url,
                'title' => $row->page_title,
                'count' => $row->count
            ])
            ->toArray();

        // 5. توزيع الساعات
        $hourlyStats = $visits->select(DB::raw('HOUR(visited_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // حفظ الملخص (update or create)
        VisitSummary::updateOrCreate(
            ['date' => $date->toDateString()],
            [
                'total_visits' => $totalVisits,
                'unique_visitors' => $uniqueVisitors,
                'total_sessions' => $totalSessions,
                'device_stats' => $deviceStats,
                'browser_stats' => $browserStats,
                'top_pages' => $topPages,
                'hourly_stats' => $hourlyStats,
            ]
        );

        $this->info("Summary created successfully!");
    }
}
