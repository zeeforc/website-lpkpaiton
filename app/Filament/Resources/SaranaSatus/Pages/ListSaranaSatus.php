<?php

namespace App\Filament\Resources\SaranaSatus\Pages;

use App\Filament\Resources\SaranaSatus\SaranaSatuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSaranaSatus extends ListRecords
{
    protected static string $resource = SaranaSatuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
