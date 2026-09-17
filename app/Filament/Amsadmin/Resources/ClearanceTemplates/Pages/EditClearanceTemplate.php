<?php

namespace App\Filament\Amsadmin\Resources\ClearanceTemplates\Pages;

use App\Filament\Amsadmin\Resources\ClearanceTemplates\ClearanceTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClearanceTemplate extends EditRecord
{
    protected static string $resource = ClearanceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
