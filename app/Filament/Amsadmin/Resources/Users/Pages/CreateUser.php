<?php

namespace App\Filament\Amsadmin\Resources\Users\Pages;

use App\Filament\Amsadmin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
