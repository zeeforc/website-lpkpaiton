<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\PavingLeaveRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPavingLeaveRequests extends ListRecords
{
    protected static string $resource = PavingLeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
