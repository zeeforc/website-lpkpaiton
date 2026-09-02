<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\ReportSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReportSubmissions extends ListRecords
{
    protected static string $resource = ReportSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
