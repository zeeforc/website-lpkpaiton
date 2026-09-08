<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages\CreatePavingLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages\EditPavingLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages\ListPavingLeaveRequests;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages\ViewPavingLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Schemas\PavingLeaveRequestForm;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Schemas\PavingLeaveRequestInfolist;
use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Tables\PavingLeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PavingLeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope-open';


    protected static ?string $recordTitleAttribute = 'reason';

    protected static ?string $navigationLabel = 'Pengajuan Izin Karyawan';
    protected static string | \UnitEnum | null $navigationGroup = 'Karyawan Paving';
    protected static ?string $modelLabel = 'Izin Karyawan';
    protected static ?string $pluralModelLabel = 'Pengajuan Izin Karyawan';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('user', function ($query) {
            $query->where('role', 'karyawan_paving');
        });
    }

    public static function form(Schema $schema): Schema
    {
        return PavingLeaveRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PavingLeaveRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PavingLeaveRequestsTable::configure($table);
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
            'index' => ListPavingLeaveRequests::route('/'),
            'create' => CreatePavingLeaveRequest::route('/create'),
            'view' => ViewPavingLeaveRequest::route('/{record}'),
            'edit' => EditPavingLeaveRequest::route('/{record}/edit'),
        ];
    }
}
