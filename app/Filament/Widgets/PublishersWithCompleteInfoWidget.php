<?php

namespace App\Filament\Widgets;

use App\Models\Publisher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PublishersWithCompleteInfoWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('الناشرين - معلومات كاملة', 
                Publisher::whereNotNull('name')
                    ->whereNotNull('country')
                    ->whereNotNull('address')
                    ->where('name', '!=', '')
                    ->count()
            )
                ->description('ناشرين لديهم معلومات كاملة')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning')
                ->icon('heroicon-o-building-library'),
        ];
    }
}
