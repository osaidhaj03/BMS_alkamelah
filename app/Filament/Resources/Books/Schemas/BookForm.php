<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الكتاب الأساسية')
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان الكتاب')
                            ->required()
                            ->columnSpan(2),
                        
                        Textarea::make('description')
                            ->label('وصف الكتاب')
                            ->default(null)
                            ->columnSpanFull(),

                        TextInput::make('shamela_id')
                            ->label('معرف الشاملة')
                            ->numeric()
                            ->default(null),

                        Select::make('visibility')
                            ->label('الظهور')
                            ->options([
                                'public' => 'عام',
                                'private' => 'خاص',
                                'restricted' => 'مقيّد'
                            ])
                            ->default('public')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('التصنيف')
                    ->schema([
                        Select::make('book_section_id')
                            ->label('القسم')
                            ->relationship('bookSection', 'name')
                            ->searchable()
                            ->preload()
                            ->default(null),
                    ]),

                Section::make('معلومات النشر')
                    ->schema([
                        Select::make('publisher_id')
                            ->label('الناشر')
                            ->relationship('publisher', 'name')
                            ->searchable()
                            ->preload()
                            ->default(null),
                        
                        Toggle::make('has_original_pagination')
                            ->label('يحتوي على ترقيم الصفحات الأصلي'),
                    ]),

                Section::make('معلومات إضافية')
                    ->schema([
                        Textarea::make('additional_notes')
                            ->label('ملاحظات إضافية')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
