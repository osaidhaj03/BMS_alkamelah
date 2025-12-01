<?php

namespace App\Filament\Resources\Volumes;

use App\Filament\Resources\Volumes\Pages\CreateVolume;
use App\Filament\Resources\Volumes\Pages\EditVolume;
use App\Filament\Resources\Volumes\Pages\ListVolumes;
use App\Filament\Resources\Volumes\Pages\ViewVolume;
use App\Filament\Resources\Volumes\Schemas\VolumeForm;
use App\Filament\Resources\Volumes\Schemas\VolumeInfolist;
use App\Filament\Resources\Volumes\Tables\VolumesTable;
use App\Models\Volume;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VolumeResource extends Resource
{
    protected static ?string $model = Volume::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'volume_number';

    public static function getNavigationGroup(): ?string
    {
        return 'محتوى الكتب';
    }

    public static function getModelLabel(): string
    {
        return 'مجلد';
    }

    public static function getPluralModelLabel(): string
    {
        return 'المجلدات';
    }

    public static function form(Schema $schema): Schema
    {
        return VolumeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VolumeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VolumesTable::configure($table);
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
            'index' => ListVolumes::route('/'),
            'create' => CreateVolume::route('/create'),
            'view' => ViewVolume::route('/{record}'),
            'edit' => EditVolume::route('/{record}/edit'),
        ];
    }
}
