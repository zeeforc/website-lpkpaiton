<?php

namespace App\Filament\Resources\BeritaUtamas\Pages;

use App\Filament\Resources\BeritaUtamas\BeritaUtamaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeritaUtama extends EditRecord
{
    protected static string $resource = BeritaUtamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
