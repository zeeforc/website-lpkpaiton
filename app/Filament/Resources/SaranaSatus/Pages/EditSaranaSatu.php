<?php

namespace App\Filament\Resources\SaranaSatus\Pages;

use App\Filament\Resources\SaranaSatus\SaranaSatuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSaranaSatu extends EditRecord
{
    protected static string $resource = SaranaSatuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
