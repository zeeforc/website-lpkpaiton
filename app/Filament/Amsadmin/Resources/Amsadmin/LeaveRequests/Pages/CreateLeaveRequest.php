<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
}
