<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\HolidayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
