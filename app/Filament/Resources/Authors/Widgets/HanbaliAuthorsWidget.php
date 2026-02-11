<?php

namespace App\Filament\Resources\Authors\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HanbaliAuthorsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Author::where('madhhab', 'المذهب الحنبلي')->count();

        return [
            Stat::make('المذهب الحنبلي', $count)
                ->description('عدد المؤلفين من المذهب الحنبلي')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('warning'),
        ];
    }

    protected int | string | array $columnSpan = 1;
}
