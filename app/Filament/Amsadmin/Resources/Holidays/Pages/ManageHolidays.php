<?php

namespace App\Filament\Amsadmin\Resources\Holidays\Pages;

use App\Filament\Amsadmin\Resources\Holidays\HolidayResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHolidays extends ManageRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
