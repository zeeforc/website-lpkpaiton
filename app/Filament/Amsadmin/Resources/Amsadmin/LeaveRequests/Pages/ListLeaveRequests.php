<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\LeaveRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
