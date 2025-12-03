<?php

namespace App\Filament\Resources\Vimis\Pages;

use App\Filament\Resources\Vimis\VimiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVimi extends EditRecord
{
    protected static string $resource = VimiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
