<?php

namespace App\Filament\Resources\Vimis\Pages;

use App\Filament\Resources\Vimis\VimiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVimis extends ListRecords
{
    protected static string $resource = VimiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
