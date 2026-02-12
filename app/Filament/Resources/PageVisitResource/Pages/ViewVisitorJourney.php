<?php

namespace App\Filament\Resources\PageVisitResource\Pages;

use App\Filament\Resources\PageVisitResource;
use App\Models\PageVisit;
use Filament\Resources\Pages\Page;

class ViewVisitorJourney extends Page
{
    protected static string $resource = PageVisitResource::class;

    public function getView(): string
    {
        return 'filament.pages.visitor-journey';
    }

    public function getTitle(): string
    {
        return 'رحلة الزائر';
    }

    public string $sessionId = '';
    public $visits = [];

    public function mount(): void
    {
        $this->sessionId = request('session') ?? '';

        $this->visits = PageVisit::where('session_id', $this->sessionId)
            ->orderBy('visited_at')
            ->get();
    }
}
