<?php

namespace App\Filament\Resources\BookSections;

use App\Filament\Resources\BookSections\Pages\CreateBookSection;
use App\Filament\Resources\BookSections\Pages\EditBookSection;
use App\Filament\Resources\BookSections\Pages\ListBookSections;
use App\Filament\Resources\BookSections\Schemas\BookSectionForm;
use App\Filament\Resources\BookSections\Tables\BookSectionsTable;
use App\Models\BookSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookSectionResource extends Resource
{
    protected static ?string $model = BookSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return 'إدارة المكتبة';
    }

    public static function getModelLabel(): string
    {
        return 'قسم';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أقسام الكتب';
    }

    public static function form(Schema $schema): Schema
    {
        return BookSectionForm::configure($schema);
    }



    public static function table(Table $table): Table
    {
        return BookSectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookSections::route('/'),
            'create' => CreateBookSection::route('/create'),
            'edit' => EditBookSection::route('/{record}/edit'),
        ];
    }
}
