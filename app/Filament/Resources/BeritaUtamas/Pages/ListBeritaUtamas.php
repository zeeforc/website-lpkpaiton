<?php

namespace App\Filament\Resources\BeritaUtamas\Pages;

use App\Filament\Resources\BeritaUtamas\BeritaUtamaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeritaUtamas extends ListRecords
{
    protected static string $resource = BeritaUtamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
