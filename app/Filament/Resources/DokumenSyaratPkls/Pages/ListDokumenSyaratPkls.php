<?php

namespace App\Filament\Resources\DokumenSyaratPkls\Pages;

use App\Filament\Resources\DokumenSyaratPkls\DokumenSyaratPklResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDokumenSyaratPkls extends ListRecords
{
    protected static string $resource = DokumenSyaratPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
