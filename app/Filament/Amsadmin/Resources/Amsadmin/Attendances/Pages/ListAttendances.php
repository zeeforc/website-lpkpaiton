<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Attendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
