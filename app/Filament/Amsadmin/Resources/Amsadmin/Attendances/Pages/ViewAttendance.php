<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Attendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Attendances\AttendanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendance extends ViewRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
