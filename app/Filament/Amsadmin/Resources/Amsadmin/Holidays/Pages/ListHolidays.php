<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\HolidayResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
