<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles\Pages;

use App\Filament\Amsadmin\Resources\StudentProfiles\StudentProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentProfiles extends ListRecords
{
    protected static string $resource = StudentProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
