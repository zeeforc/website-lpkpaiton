<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\PavingLeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePavingLeaveRequest extends CreateRecord
{
    protected static string $resource = PavingLeaveRequestResource::class;
}
