<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\PavingAttendanceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ListPavingAttendances extends ListRecords
{
    protected static string $resource = PavingAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Download Laporan (Excel)')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('role')
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

            Action::make('download_photos')
                ->label('Download Dokumentasi (ZIP)')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('success')
                ->form([
                    Select::make('month')
                        ->label('Pilih Bulan')
                        ->options([
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ])
                        ->default(date('m'))
                        ->required(),
                    TextInput::make('year')
                        ->label('Tahun')
                        ->default(date('Y'))
                        ->numeric()
                        ->minValue(2020)
                        ->maxValue(2100)
                        ->required(),
                ])
                ->action(function (array $data, Component $livewire) {
                    $url = URL::temporarySignedRoute(
                        'admin.paving-attendances.documentation-download',
                        now()->addMinutes(30),
                        [
                            'year' => (int) $data['year'],
                            'month' => (int) $data['month'],
                        ],
                    );

                    $livewire->redirect($url, navigate: false);
                }),
            CreateAction::make(),
        ];
    }
}
