<?php

namespace App\Filament\Resources\Amsadmin\Holidays\Pages;

use App\Filament\Resources\Amsadmin\Holidays\HolidayResource;
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
