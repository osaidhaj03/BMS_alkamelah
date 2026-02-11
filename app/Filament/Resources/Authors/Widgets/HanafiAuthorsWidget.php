<?php

namespace App\Filament\Resources\Authors\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HanafiAuthorsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Author::where('madhhab', 'المذهب الحنفي')->count();

        return [
            Stat::make('المذهب الحنفي', $count)
                ->description('عدد المؤلفين من المذهب الحنفي')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary'),
        ];
    }

    protected int | string | array $columnSpan = 1;
}
