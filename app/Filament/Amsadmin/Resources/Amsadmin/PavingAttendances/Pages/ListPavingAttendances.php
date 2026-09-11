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
                ->form([
                    \Filament\Forms\Components\Select::make('role')
                        ->label('Pilih Data Role')
                        ->options([
                            'karyawan_paving' => 'Karyawan Paving',
                            'instruktur_lpk' => 'Instruktur LPK',
                            'semua' => 'Semua Karyawan',
                        ])
                        ->default('karyawan_paving')
                        ->required(),
                ])
                ->action(function (array $data) {
                    return redirect()->route('admin.paving-attendances.export', ['role' => $data['role']]);
                }),
            CreateAction::make(),
        ];
    }
}
