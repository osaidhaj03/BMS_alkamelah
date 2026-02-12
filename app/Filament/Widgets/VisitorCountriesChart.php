<?php

namespace App\Filament\Widgets;

use App\Models\PageVisit;
use Filament\Widgets\ChartWidget;

class VisitorCountriesChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'توزيع الدول (Top 10)';
    }

    public static function getSort(): int
    {
        return 24;
    }

    protected function getData(): array
    {
        $countries = PageVisit::humans()
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'country')
            ->toArray();

        $colors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6',
                    '#06b6d4','#ec4899','#84cc16','#f97316','#6366f1'];

        return [
            'datasets' => [[
                'data' => array_values($countries),
                'backgroundColor' => array_slice($colors, 0, count($countries)),
            ]],
            'labels' => array_keys($countries),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
