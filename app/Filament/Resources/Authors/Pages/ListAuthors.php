<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthors extends ListRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AuthorResource::getWidgets()[0], // AuthorsStatsWidget
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AuthorResource::getWidgets()[1], // HanafiAuthorsWidget
            AuthorResource::getWidgets()[2], // ShafiAuthorsWidget
            AuthorResource::getWidgets()[3], // HanbaliAuthorsWidget
            AuthorResource::getWidgets()[4], // MalikiAuthorsWidget
        ];
    }
}
