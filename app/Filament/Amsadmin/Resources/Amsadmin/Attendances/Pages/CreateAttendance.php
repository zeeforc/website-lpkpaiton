<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Attendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
