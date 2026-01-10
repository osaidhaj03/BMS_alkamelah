<?php

namespace App\Filament\Widgets;

use App\Models\FeedbackComplaint;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeedbackComplaintsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('الشكاوي المعلقة', FeedbackComplaint::where('status', 'pending')->count())->description('تحتاج إلى مراجعة')->descriptionIcon('heroicon-m-clock')->color('warning')->icon('heroicon-o-exclamation-circle'),
            Stat::make('قيد المراجعة', FeedbackComplaint::where('status', 'under_review')->count())->description('جاري العمل عليها')->descriptionIcon('heroicon-m-eye')->color('info')->icon('heroicon-o-eye'),
            Stat::make('تم الحل', FeedbackComplaint::where('status', 'resolved')->count())->description('تم معالجتها بنجاح')->descriptionIcon('heroicon-m-check')->color('success')->icon('heroicon-o-check-circle'),
            Stat::make('عالية الأولوية', FeedbackComplaint::whereIn('priority', ['high', 'urgent'])->where('status', '!=', 'resolved')->count())->description('تحتاج اهتماماً فورياً')->descriptionIcon('heroicon-m-fire')->color('danger')->icon('heroicon-o-fire'),
        ];
    }
}
