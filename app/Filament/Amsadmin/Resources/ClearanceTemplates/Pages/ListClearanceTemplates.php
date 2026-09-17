<?php

namespace App\Filament\Amsadmin\Resources\ClearanceTemplates\Pages;

use App\Filament\Amsadmin\Resources\ClearanceTemplates\ClearanceTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClearanceTemplates extends ListRecords
{
    protected static string $resource = ClearanceTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
