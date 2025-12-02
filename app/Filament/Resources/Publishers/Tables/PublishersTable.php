<?php

namespace App\Filament\Resources\Publishers\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class PublishersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الناشر')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('website_url')
                    ->label('الموقع الإلكتروني')
                    ->toggleable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('نشط')
                    ->toggleable()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country')
                    ->label('الدولة')
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('نشط')
                    ->options([
                        true => 'نعم',
                        false => 'لا',
                    ]),
                SelectFilter::make('country')
                    ->label('الدولة')
                    ->options([
                        'المملكة العربية السعودية' => 'المملكة العربية السعودية',
                        'مصر' => 'مصر',
                        'الإمارات العربية المتحدة' => 'الإمارات العربية المتحدة',
                        'الكويت' => 'الكويت',
                        'قطر' => 'قطر',
                        'البحرين' => 'البحرين',
                        'عمان' => 'عمان',
                        'الأردن' => 'الأردن',
                        'لبنان' => 'لبنان',
                        'سوريا' => 'سوريا',
                        'العراق' => 'العراق',
                        'اليمن' => 'اليمن',
                        'فلسطين' => 'فلسطين',
                        'المغرب' => 'المغرب',
                        'الجزائر' => 'الجزائر',
                        'تونس' => 'تونس',
                        'ليبيا' => 'ليبيا',
                        'السودان' => 'السودان',
                        'موريتانيا' => 'موريتانيا',
                        'الصومال' => 'الصومال',
                        'جيبوتي' => 'جيبوتي',
                        'جزر القمر' => 'جزر القمر',
                    ]),

                //
            ])
            ->recordActions([
                //ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('تصدير')
                    ->color('success'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    FilamentExportBulkAction::make('export')
                        ->label('تصدير')
                        ->color('success'),
                ]),
            ]);
    }
}
