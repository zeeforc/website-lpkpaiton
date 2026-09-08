<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests;

use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages\ViewLeaveRequest;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Schemas\LeaveRequestInfolist;
use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reason';

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeaveRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
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
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'view' => ViewLeaveRequest::route('/{record}'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
