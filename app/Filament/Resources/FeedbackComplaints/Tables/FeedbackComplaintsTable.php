<?php

namespace App\Filament\Resources\FeedbackComplaints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedbackComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                BadgeColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'complaint' => 'شكوى',
                        'suggestion' => 'اقتراح',
                        'feedback' => 'ملاحظة',
                        'inquiry' => 'استفسار',
                        default => $state,
                    })
                    ->colors([
                        'danger' => 'complaint',
                        'success' => 'suggestion',
                        'info' => 'feedback',
                        'warning' => 'inquiry',
                    ]),

                BadgeColumn::make('category')
                    ->label('التصنيف')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'book' => 'كتاب',
                        'author' => 'مؤلف',
                        'search' => 'نتائج البحث',
                        'page' => 'صفحة',
                        'general' => 'عام',
                        default => $state,
                    }),

                TextColumn::make('subject')
                    ->label('الموضوع')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('البريد')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'under_review' => 'قيد المراجعة',
                        'resolved' => 'تم الحل',
                        'rejected' => 'مرفوض',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'under_review',
                        'success' => 'resolved',
                        'danger' => 'rejected',
                    ]),

                BadgeColumn::make('priority')
                    ->label('الأولوية')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة',
                        default => $state,
                    })
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),

                TextColumn::make('created_at')
                    ->label('تاريخ الإرسال')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'complaint' => 'شكوى',
                        'suggestion' => 'اقتراح',
                        'feedback' => 'ملاحظة',
                        'inquiry' => 'استفسار',
                    ]),

                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'under_review' => 'قيد المراجعة',
                        'resolved' => 'تم الحل',
                        'rejected' => 'مرفوض',
                    ]),

                SelectFilter::make('priority')
                    ->label('الأولوية')
                    ->options([
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة',
                    ]),

                SelectFilter::make('category')
                    ->label('التصنيف')
                    ->options([
                        'book' => 'كتاب',
                        'author' => 'مؤلف',
                        'search' => 'نتائج البحث',
                        'page' => 'صفحة',
                        'general' => 'عام',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
