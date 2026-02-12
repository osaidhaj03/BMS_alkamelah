<?php

namespace App\Filament\Widgets;

use App\Models\PageVisit;
use Filament\Widgets\Widget;

class RealTimeVisitorsWidget extends Widget
{
    protected static string $view = 'filament.widgets.real-time-visitors';
    protected int|string|array $columnSpan = 'full';

    // تحديث كل 10 ثواني
    protected static ?string $pollingInterval = '10s';

    public static function getSort(): int
    {
        return 5;
    }

    public function getVisitorsData(): array
    {
        $cutoff = now()->subMinutes(5);

        $activeVisitors = PageVisit::humans()
            ->where('visited_at', '>=', $cutoff)
            ->distinct('ip_address')
            ->count('ip_address');

        $activePages = PageVisit::humans()
            ->where('visited_at', '>=', $cutoff)
            ->selectRaw('page_title, route_name, COUNT(DISTINCT ip_address) as visitors')
            ->groupBy('page_title', 'route_name')
            ->orderByDesc('visitors')
            ->limit(10)
            ->get();

        return [
            'count' => $activeVisitors,
            'pages' => $activePages,
        ];
    }
}
