<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Pages;

use App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\PavingAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

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
                ->action(function (array $data, \Livewire\Component $livewire) {
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
                    $archiveName = \Illuminate\Support\Str::uuid() . '.zip';
                    $archiveDirectory = storage_path('app/private/attendance-exports');
                    \Illuminate\Support\Facades\File::ensureDirectoryExists($archiveDirectory);
                    $zipPath = $archiveDirectory . DIRECTORY_SEPARATOR . $archiveName;

                    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                        $hasFiles = false;
                        $addFileToZip = function (string $filePath, string $archivePath) use ($zip, &$hasFiles): bool {
                            if (!is_file($filePath) || !is_readable($filePath)) {
                                Log::warning('Skipped invalid attendance documentation path.', [
                                    'path' => $filePath,
                                ]);
                                return false;
                            }

                            if (!$zip->addFile($filePath, $archivePath)) {
                                Log::warning('Failed to add attendance documentation file to ZIP.', [
                                    'path' => $filePath,
                                    'archive_path' => $archivePath,
                                ]);
                                return false;
                            }

                            $hasFiles = true;
                            return true;
                        };

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
                                $checkInDate = clone $dateObj;
                                if ($attendance->check_in) {
                                    $checkInDate->setTimeFromTimeString($attendance->check_in);
                                }
                                $fileName = $checkInDate->format('Y-m-d_H-i-s') . '_Masuk.jpg';
                                $addFileToZip($filePath, $fullFolderName . '/' . $fileName);
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
                                        if (!is_string($cp) || $cp === '') {
                                            continue;
                                        }

                                        $filePath = storage_path('app/public/' . $cp);
                                        $fileName = $checkOutDate->format('Y-m-d_H-i-s') . '_Dokumentasi_' . $counter . '.jpg';
                                        if ($addFileToZip($filePath, $fullFolderName . '/' . $fileName)) {
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

                        $livewire->redirect(\Illuminate\Support\Facades\URL::temporarySignedRoute(
                            'admin.paving-attendances.documentation-download',
                            now()->addMinutes(10),
                            [
                                'archive' => $archiveName,
                                'download_name' => 'Dokumentasi Karyawan - ' . $monthName . ' ' . $year . '.zip',
                            ],
                        ), navigate: false);
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
