<?php

namespace App\Filament\Resources\PklStats\PklStatResource\Pages;

use App\Filament\Resources\PklStats\PklStatResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPklStat extends EditRecord
{
    protected static string $resource = PklStatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
