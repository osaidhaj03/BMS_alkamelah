<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewedBooksWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('الكتب المراجعة', Book::where('is_reviewed', true)->count())
                ->description('عدد الكتب التي تم مراجعتها')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-badge'),
        ];
    }
}
