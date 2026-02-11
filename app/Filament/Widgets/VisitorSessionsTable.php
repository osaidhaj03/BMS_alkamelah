<?php

namespace App\Filament\Widgets;

use App\Models\PageVisit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class VisitorSessionsTable extends BaseWidget
{
    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'جلسات الزوار الأخيرة';

    protected function getTableRecordKey($record): string
    {
        return $record->session_id ?? uniqid();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PageVisit::query()
                    ->where('is_bot', false)
                    ->whereNotNull('session_id')
                    ->select('session_id', 'ip_address')
                    ->selectRaw('COUNT(*) as pages_count')
                    ->selectRaw('SUM(COALESCE(duration_seconds, 0)) as total_duration')
                    ->selectRaw('MIN(visited_at) as first_visit')
                    ->selectRaw('MAX(visited_at) as last_visit')
                    ->groupBy('session_id', 'ip_address')
                    ->orderByDesc('first_visit')
                    ->limit(30)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('عنوان IP')
                    ->copyable()
                    ->copyMessage('تم النسخ!')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pages_count')
                    ->label('عدد الصفحات')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 5 => 'success',
                        $state >= 3 => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_duration')
                    ->label('إجمالي الوقت')
                    ->formatStateUsing(fn ($state) => $state > 0
                        ? PageVisit::formatDuration((int) $state)
                        : '-'
                    )
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray'),

                Tables\Columns\TextColumn::make('first_visit')
                    ->label('بداية الجلسة')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_visit')
                    ->label('آخر نشاط')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('first_visit', 'desc');
    }
}
