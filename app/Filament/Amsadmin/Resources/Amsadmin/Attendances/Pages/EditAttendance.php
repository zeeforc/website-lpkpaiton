<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Attendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Attendances\AttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
