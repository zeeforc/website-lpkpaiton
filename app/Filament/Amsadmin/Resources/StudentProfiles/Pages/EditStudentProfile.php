<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles\Pages;

use App\Filament\Amsadmin\Resources\StudentProfiles\StudentProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentProfile extends EditRecord
{
    protected static string $resource = StudentProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
