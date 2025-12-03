<?php

namespace App\Filament\Resources\Saranas\Pages;

use App\Filament\Resources\Saranas\SaranaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSarana extends EditRecord
{
    protected static string $resource = SaranaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
