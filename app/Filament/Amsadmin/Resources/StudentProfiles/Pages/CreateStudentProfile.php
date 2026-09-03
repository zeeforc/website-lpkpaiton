<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles\Pages;

use App\Filament\Amsadmin\Resources\StudentProfiles\StudentProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentProfile extends CreateRecord
{
    protected static string $resource = StudentProfileResource::class;
}
