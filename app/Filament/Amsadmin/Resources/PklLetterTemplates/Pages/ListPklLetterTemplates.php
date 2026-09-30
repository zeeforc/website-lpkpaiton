<?php

namespace App\Filament\Amsadmin\Resources\PklLetterTemplates\Pages;

use App\Filament\Amsadmin\Resources\PklLetterTemplates\PklLetterTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPklLetterTemplates extends ListRecords
{
    protected static string $resource = PklLetterTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
