<?php

namespace App\Filament\Resources\FeedbackComplaints\Pages;

use App\Filament\Resources\FeedbackComplaints\FeedbackComplaintResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedbackComplaint extends EditRecord
{
    protected static string $resource = FeedbackComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
