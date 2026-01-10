<?php

namespace App\Filament\Resources\FeedbackComplaints\Pages;

use App\Filament\Resources\FeedbackComplaints\FeedbackComplaintResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedbackComplaint extends CreateRecord
{
    protected static string $resource = FeedbackComplaintResource::class;
}
