<?php

namespace App\Filament\Resources\PageVisitResource\Pages;

use App\Filament\Resources\PageVisitResource;
use Filament\Resources\Pages\ListRecords;

class ListPageVisits extends ListRecords
{
    protected static string $resource = PageVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\RealTimeVisitorsWidget::class,
            \App\Filament\Widgets\VisitorStatsOverview::class,
            \App\Filament\Widgets\VisitsLineChart::class,
            \App\Filament\Widgets\HourlyVisitsChart::class,
            \App\Filament\Widgets\TopPagesChart::class,
            \App\Filament\Widgets\DeviceDistributionChart::class,
            \App\Filament\Widgets\VisitorCountriesChart::class,
            \App\Filament\Widgets\TrafficSourcesChart::class,
        ];
    }
}
