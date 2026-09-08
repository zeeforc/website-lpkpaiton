<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\PavingLeaveRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPavingLeaveRequest extends ViewRecord
{
    protected static string $resource = PavingLeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
