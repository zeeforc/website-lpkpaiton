<?php

namespace App\Filament\Resources\DokumenSyaratPkls\Pages;

use App\Filament\Resources\DokumenSyaratPkls\DokumenSyaratPklResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDokumenSyaratPkl extends EditRecord
{
    protected static string $resource = DokumenSyaratPklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
