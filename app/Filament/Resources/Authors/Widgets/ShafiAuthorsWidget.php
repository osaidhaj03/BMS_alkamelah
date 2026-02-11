<?php

namespace App\Filament\Resources\Authors\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShafiAuthorsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Author::where('madhhab', 'المذهب الشافعي')->count();

        return [
            Stat::make('المذهب الشافعي', $count)
                ->description('عدد المؤلفين من المذهب الشافعي')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),
        ];
    }

    protected int | string | array $columnSpan = 1;
}
