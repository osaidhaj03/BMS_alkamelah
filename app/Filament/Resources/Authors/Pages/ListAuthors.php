<?php

namespace App\Filament\Resources\Authors\Pages;

use App\Filament\Resources\Authors\AuthorResource;
use App\Filament\Resources\Authors\Widgets\AuthorsStatsWidget;
use App\Filament\Resources\Authors\Widgets\HanafiAuthorsWidget;
use App\Filament\Resources\Authors\Widgets\ShafiAuthorsWidget;
use App\Filament\Resources\Authors\Widgets\HanbaliAuthorsWidget;
use App\Filament\Resources\Authors\Widgets\MalikiAuthorsWidget;
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
            AuthorsStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            HanafiAuthorsWidget::class,
            ShafiAuthorsWidget::class,
            HanbaliAuthorsWidget::class,
            MalikiAuthorsWidget::class,
        ];
    }
}
