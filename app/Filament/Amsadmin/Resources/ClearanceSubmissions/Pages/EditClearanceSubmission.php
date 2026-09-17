<?php

namespace App\Filament\Amsadmin\Resources\ClearanceSubmissions\Pages;

use App\Filament\Amsadmin\Resources\ClearanceSubmissions\ClearanceSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClearanceSubmission extends EditRecord
{
    protected static string $resource = ClearanceSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
