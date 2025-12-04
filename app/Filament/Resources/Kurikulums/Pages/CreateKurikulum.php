<?php

namespace App\Filament\Resources\Kurikulums\Pages;

use App\Filament\Resources\Kurikulums\KurikulumResource;
use App\Services\ExcelToHtml;
use Filament\Resources\Pages\CreateRecord;

class CreateKurikulum extends CreateRecord
{
    protected static string $resource = KurikulumResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil semua state form termasuk file upload
        $state = $this->form->getState();

        if (! empty($state['matrix_excel'])) {
            $file = $state['matrix_excel'];

            // Antisipasi kalau Filament mengembalikan array
            if (is_array($file)) {
                $file = $file[0];
            }

            // Path fisik file di storage
            $fullPath = storage_path('app/public/' . $file);

            // Konversi ke HTML
            $data['matrix_html'] = ExcelToHtml::convert($fullPath);
        }

        return $data;
    }
}
