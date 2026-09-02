<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\ReportSubmissionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReportSubmission extends ViewRecord
{
    protected static string $resource = ReportSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
