<?php

namespace App\Filament\Resources\PklStats\PklStatResource\Pages;

use App\Filament\Resources\PklStats\PklStatResource;
use Filament\Resources\Pages\ListRecords;

class ListPklStats extends ListRecords
{
    protected static string $resource = PklStatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
