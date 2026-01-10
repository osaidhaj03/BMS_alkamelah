<?php

namespace App\Filament\Resources\FeedbackComplaints;

use App\Filament\Resources\FeedbackComplaints\Pages\CreateFeedbackComplaint;
use App\Filament\Resources\FeedbackComplaints\Pages\EditFeedbackComplaint;
use App\Filament\Resources\FeedbackComplaints\Pages\ListFeedbackComplaints;
use App\Filament\Resources\FeedbackComplaints\Schemas\FeedbackComplaintForm;
use App\Filament\Resources\FeedbackComplaints\Tables\FeedbackComplaintsTable;
use App\Models\FeedbackComplaint;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedbackComplaintResource extends Resource
{
    protected static ?string $model = FeedbackComplaint::class;

    protected static ?string $navigationLabel = 'الشكاوي والملاحظات';

    protected static ?string $pluralLabel = 'الشكاوي والملاحظات';

    protected static UnitEnum|string|null $navigationGroup = 'التواصل';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'subject';

    public static function form(Schema $schema): Schema
    {
        return FeedbackComplaintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedbackComplaintsTable::configure($table);
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
            'index' => ListFeedbackComplaints::route('/'),
            'create' => CreateFeedbackComplaint::route('/create'),
            'edit' => EditFeedbackComplaint::route('/{record}/edit'),
        ];
    }
}
