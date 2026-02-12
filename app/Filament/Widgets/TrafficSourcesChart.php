<?php

namespace App\Filament\Widgets;

use App\Models\PageVisit;
use Filament\Widgets\ChartWidget;

class TrafficSourcesChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'مصادر الزيارات';
    }

    public static function getSort(): int
    {
        return 25;
    }

    protected function getData(): array
    {
        $sources = PageVisit::humans()
            ->whereNotNull('source_type')
            ->selectRaw('source_type, COUNT(*) as count')
            ->groupBy('source_type')
            ->orderByDesc('count')
            ->pluck('count', 'source_type')
            ->toArray();

        $labels = array_map(fn ($s) => match($s) {
            'google' => '🔍 Google', 'direct' => '🔗 مباشر',
            'facebook' => '📘 Facebook', 'twitter' => '🐦 Twitter',
            'internal' => '🏠 داخلي', 'bing' => '🔍 Bing',
            'youtube' => '▶️ YouTube', 'whatsapp' => '💬 WhatsApp',
            'telegram' => '✈️ Telegram', default => ucfirst($s),
        }, array_keys($sources));

        return [
            'datasets' => [[
                'data' => array_values($sources),
                'backgroundColor' => ['#4285f4','#34a853','#1877f2','#1da1f2','#6366f1','#f59e0b','#ef4444','#8b5cf6','#06b6d4'],
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
