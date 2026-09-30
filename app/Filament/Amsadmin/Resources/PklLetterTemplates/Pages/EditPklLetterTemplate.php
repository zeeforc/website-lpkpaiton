<?php

namespace App\Filament\Amsadmin\Resources\PklLetterTemplates\Pages;

use App\Filament\Amsadmin\Resources\PklLetterTemplates\PklLetterTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPklLetterTemplate extends EditRecord
{
    protected static string $resource = PklLetterTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
