<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\PavingLeaveRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPavingLeaveRequest extends EditRecord
{
    protected static string $resource = PavingLeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
