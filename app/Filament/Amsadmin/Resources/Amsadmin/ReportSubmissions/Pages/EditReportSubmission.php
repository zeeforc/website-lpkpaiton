<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\ReportSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditReportSubmission extends EditRecord
{
    protected static string $resource = ReportSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
