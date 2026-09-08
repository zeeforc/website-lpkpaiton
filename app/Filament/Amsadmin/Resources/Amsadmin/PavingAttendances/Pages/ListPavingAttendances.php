<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\PavingAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPavingAttendances extends ListRecords
{
    protected static string $resource = PavingAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv')
                ->label('Download Laporan (Excel)')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn () => route('admin.paving-attendances.export'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
