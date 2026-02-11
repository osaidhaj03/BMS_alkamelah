<?php

namespace App\Filament\Widgets;

use App\Models\PageVisit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorStatsOverview extends BaseWidget
{
    protected int|array|null $columns = 4;

    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        $avgDuration = PageVisit::avgDuration();

        return [
            Stat::make('إجمالي الزيارات', number_format(PageVisit::totalHumanVisits()))
                ->description('بدون Bots')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-eye'),

            Stat::make('زوار فريدين (IP)', number_format(PageVisit::uniqueVisitorsCount()))
                ->description('عدد عناوين IP الفريدة')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->icon('heroicon-o-user-group'),

            Stat::make('زيارات اليوم', number_format(PageVisit::todayVisits()))
                ->description(now()->format('Y-m-d'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning')
                ->icon('heroicon-o-calendar'),

            Stat::make('متوسط الوقت/صفحة', PageVisit::formatDuration((int) $avgDuration))
                ->description('للزوار الحقيقيين')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary')
                ->icon('heroicon-o-clock'),

            Stat::make('زيارات الشهر', number_format(PageVisit::thisMonthVisits()))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('الجلسات الفريدة', number_format(PageVisit::uniqueSessionsCount()))
                ->description('عدد الجلسات')
                ->descriptionIcon('heroicon-m-finger-print')
                ->color('info')
                ->icon('heroicon-o-finger-print'),

            Stat::make('صفحات/جلسة', PageVisit::avgPagesPerSession())
                ->description('متوسط الصفحات لكل جلسة')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('warning')
                ->icon('heroicon-o-document-duplicate'),

            Stat::make('زيارات Bots', number_format(PageVisit::bots()->count()))
                ->description('Googlebot, Bingbot, ...')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('gray')
                ->icon('heroicon-o-cpu-chip'),
        ];
    }
}
