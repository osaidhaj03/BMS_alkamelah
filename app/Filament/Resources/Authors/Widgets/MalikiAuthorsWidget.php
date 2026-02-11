<?php

namespace App\Filament\Resources\Authors\Widgets;

use App\Models\Author;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MalikiAuthorsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Author::where('madhhab', 'المذهب المالكي')->count();

        return [
            Stat::make('المذهب المالكي', $count)
                ->description('عدد المؤلفين من المذهب المالكي')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info'),
        ];
    }

    protected int | string | array $columnSpan = 1;
}
