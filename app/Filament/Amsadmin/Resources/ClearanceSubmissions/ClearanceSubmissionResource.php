<?php

namespace App\Filament\Amsadmin\Resources\ClearanceSubmissions;

use App\Filament\Amsadmin\Resources\ClearanceSubmissions\Pages\CreateClearanceSubmission;
use App\Filament\Amsadmin\Resources\ClearanceSubmissions\Pages\EditClearanceSubmission;
use App\Filament\Amsadmin\Resources\ClearanceSubmissions\Pages\ListClearanceSubmissions;
use App\Filament\Amsadmin\Resources\ClearanceSubmissions\Schemas\ClearanceSubmissionForm;
use App\Filament\Amsadmin\Resources\ClearanceSubmissions\Tables\ClearanceSubmissionsTable;
use App\Models\ClearanceSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClearanceSubmissionResource extends Resource
{
    protected static ?string $model = ClearanceSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Pengajuan Surat Bebas Tanggungan';
    protected static string | \UnitEnum | null $navigationGroup = 'Siswa';

    public static function form(Schema $schema): Schema
    {
        return ClearanceSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClearanceSubmissionsTable::configure($table);
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
            'index' => ListClearanceSubmissions::route('/'),
        ];
    }
}
