<?php

namespace App\Filament\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuthorsWithBiographyWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('المؤلفين مع السيرة', Author::whereNotNull('biography')->where('biography', '!=', '')->count())
                ->description('عدد المؤلفين الذين لديهم سيرة ذاتية')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->icon('heroicon-o-user-circle'),
        ];
    }
}
