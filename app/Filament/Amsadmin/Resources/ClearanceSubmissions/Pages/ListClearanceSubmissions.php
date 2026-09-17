<?php

namespace App\Filament\Amsadmin\Resources\ClearanceSubmissions\Pages;

use App\Filament\Amsadmin\Resources\ClearanceSubmissions\ClearanceSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClearanceSubmissions extends ListRecords
{
    protected static string $resource = ClearanceSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
