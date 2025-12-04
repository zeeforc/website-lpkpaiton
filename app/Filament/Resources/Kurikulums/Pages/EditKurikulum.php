<?php

namespace App\Filament\Resources\Kurikulums\Pages;

use App\Filament\Resources\Kurikulums\KurikulumResource;
use App\Services\ExcelToHtml;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKurikulum extends EditRecord
{
    protected static string $resource = KurikulumResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();

        // IF USER UP NEW FILE = UDATE MATRIX_HTML
        if (! empty($state['matrix_excel'])) {
            $file = $state['matrix_excel'];

            if (is_array($file)) {
                $file = $file[0];
            }

            $fullPath = storage_path('app/public/' . $file);

            $data['matrix_html'] = ExcelToHtml::convert($fullPath);
        }

        // ELSE
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
