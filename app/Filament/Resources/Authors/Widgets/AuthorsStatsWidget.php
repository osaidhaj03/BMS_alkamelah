<?php

namespace App\Filament\Resources\Authors\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuthorsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAuthors = Author::count();
        $authorsLastMonth = Author::where('created_at', '>=', now()->subMonth())->count();
        $authorsWithoutBiography = Author::whereNull('biography')
            ->orWhere('biography', '')
            ->count();
        $authorsWithoutMadhhab = Author::whereNull('madhhab')
            ->orWhere('madhhab', '')
            ->count();

        return [
            Stat::make('إجمالي المؤلفين', $totalAuthors)
                ->description('العدد الكلي للمؤلفين')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('المضافون آخر شهر', $authorsLastMonth)
                ->description('مؤلفون جدد في آخر 30 يوم')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('info'),

            Stat::make('بدون سيرة', $authorsWithoutBiography)
                ->description('مؤلفون لا يملكون سيرة ذاتية')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make('بدون مذهب', $authorsWithoutMadhhab)
                ->description('مؤلفون لم يحدد مذهبهم')
                ->descriptionIcon('heroicon-o-bookmark')
                ->color('danger'),
        ];
    }
}
