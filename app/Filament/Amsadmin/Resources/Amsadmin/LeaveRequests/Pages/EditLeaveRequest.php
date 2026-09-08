<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\LeaveRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
