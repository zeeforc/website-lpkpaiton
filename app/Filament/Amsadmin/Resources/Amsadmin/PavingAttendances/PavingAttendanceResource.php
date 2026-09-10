<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages\CreatePavingAttendance;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages\EditPavingAttendance;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages\ListPavingAttendances;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages\ViewPavingAttendance;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Schemas\PavingAttendanceForm;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Schemas\PavingAttendanceInfolist;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Tables\PavingAttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PavingAttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Absensi Karyawan';
    protected static string | \UnitEnum | null $navigationGroup = 'Karyawan';
    protected static ?string $modelLabel = 'Absensi Karyawan';
    protected static ?string $pluralModelLabel = 'Absensi Karyawan';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('user', function ($query) {
            $query->whereIn('role', ['karyawan_paving', 'instruktur_lpk']);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return PavingAttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PavingAttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PavingAttendancesTable::configure($table);
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
            'index' => ListPavingAttendances::route('/'),
            'create' => CreatePavingAttendance::route('/create'),
            'view' => ViewPavingAttendance::route('/{record}'),
            'edit' => EditPavingAttendance::route('/{record}/edit'),
        ];
    }
}
