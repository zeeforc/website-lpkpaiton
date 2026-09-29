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

            \Filament\Actions\Action::make('download_photos')
                ->label('Download Dokumentasi (ZIP)')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('month')
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
                    \Filament\Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->default(date('Y'))
                        ->numeric()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $month = $data['month'];
                    $year = $data['year'];
                    $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F');
                    
                    $attendances = \App\Models\Attendance::whereHas('user', function ($query) {
                        $query->whereIn('role', ['karyawan_paving', 'instruktur_lpk']);
                    })
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where(function($q) {
                        $q->whereNotNull('photo_path')->orWhereNotNull('checkout_photos');
                    })
                    ->with('user')
                    ->get();

                    if ($attendances->isEmpty()) {
                        \Filament\Notifications\Notification::make()
                            ->title('Tidak ada dokumentasi')
                            ->body('Tidak ditemukan foto dokumentasi karyawan pada bulan ' . $monthName . ' ' . $year)
                            ->warning()
                            ->send();
                        return;
                    }

                    $zip = new \ZipArchive();
                    $zipFileName = 'Dokumentasi Karyawan - ' . $monthName . ' ' . $year . '.zip';
                    $zipPath = storage_path('app/public/' . $zipFileName);

                    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                        $hasFiles = false;
                        foreach ($attendances as $attendance) {
                            $userName = preg_replace('/[^A-Za-z0-9\-\s]/', '', $attendance->user->name ?? 'Unknown');
                            $roleMap = [
                                'karyawan_paving' => 'Karyawan Paving',
                                'instruktur_lpk' => 'Instruktur LPK'
                            ];
                            $userRole = $roleMap[$attendance->user->role ?? ''] ?? 'Karyawan';
                            $folderName = $userName . ' - ' . $userRole;
                            
                            $dateObj = \Carbon\Carbon::parse($attendance->date);
                            $dateFolderName = strtolower($dateObj->translatedFormat('d-F'));
                            $fullFolderName = $folderName . '/' . $dateFolderName;
                            
                            // 1. Foto Masuk (photo_path)
                            if ($attendance->photo_path) {
                                $filePath = storage_path('app/public/' . $attendance->photo_path);
                                if (file_exists($filePath)) {
                                    $hasFiles = true;
                                    $checkInDate = clone $dateObj;
                                    if ($attendance->check_in) {
                                        $checkInDate->setTimeFromTimeString($attendance->check_in);
                                    }
                                    $fileName = $checkInDate->format('Y-m-d_H-i-s') . '_Masuk.jpg';
                                    $zip->addFile($filePath, $fullFolderName . '/' . $fileName);
                                }
                            }

                            // 2. Foto Dokumentasi / Pulang (checkout_photos)
                            if ($attendance->checkout_photos) {
                                $checkoutPhotos = is_array($attendance->checkout_photos) 
                                    ? $attendance->checkout_photos 
                                    : json_decode($attendance->checkout_photos, true);
                                    
                                if (is_array($checkoutPhotos)) {
                                    $checkOutDate = clone $dateObj;
                                    if ($attendance->check_out) {
                                        $checkOutDate->setTimeFromTimeString($attendance->check_out);
                                    } else {
                                        // Default jam jika belum checkout tapi sudah upload (seharusnya tidak terjadi)
                                        $checkOutDate->setTime(16, 0, 0); 
                                    }
                                    
                                    $counter = 1;
                                    foreach ($checkoutPhotos as $cp) {
                                        $filePath = storage_path('app/public/' . $cp);
                                        if (file_exists($filePath)) {
                                            $hasFiles = true;
                                            $fileName = $checkOutDate->format('Y-m-d_H-i-s') . '_Dokumentasi_' . $counter . '.jpg';
                                            $zip->addFile($filePath, $fullFolderName . '/' . $fileName);
                                            $counter++;
                                        }
                                    }
                                }
                            }
                        }
                        $zip->close();
                        
                        if (!$hasFiles) {
                            \Filament\Notifications\Notification::make()
                                ->title('File Tidak Ditemukan')
                                ->body('Data absen ada, tapi file fisik fotonya tidak ditemukan di server.')
                                ->danger()
                                ->send();
                            return;
                        }

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal')
                            ->body('Gagal membuat file ZIP.')
                            ->danger()
                            ->send();
                        return;
                    }
                }),
            CreateAction::make(),
        ];
    }
}
