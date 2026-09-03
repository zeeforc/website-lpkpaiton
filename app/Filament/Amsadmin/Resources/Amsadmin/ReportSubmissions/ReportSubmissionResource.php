<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions;

use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages\CreateReportSubmission;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages\EditReportSubmission;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages\ListReportSubmissions;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages\ViewReportSubmission;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Schemas\ReportSubmissionForm;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Schemas\ReportSubmissionInfolist;
use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Tables\ReportSubmissionsTable;
use App\Models\ReportSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReportSubmissionResource extends Resource
{
    protected static ?string $model = ReportSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Laporan Siswa';
    
    protected static ?string $modelLabel = 'Laporan Siswa';
    
    protected static ?string $pluralModelLabel = 'Laporan Siswa';

    protected static ?string $recordTitleAttribute = 'title';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ReportSubmissionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReportSubmissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportSubmissionsTable::configure($table);
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
            'index' => ListReportSubmissions::route('/'),
            'view' => ViewReportSubmission::route('/{record}'),
            'edit' => EditReportSubmission::route('/{record}/edit'),
        ];
    }
}
