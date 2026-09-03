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
            \Filament\Actions\Action::make('approve')
                ->label('Terima Laporan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'approved']);
                    \Filament\Notifications\Notification::make()->title('Laporan diterima')->success()->send();
                })
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status !== 'approved'),
            \Filament\Actions\Action::make('reject')
                ->label('Tolak / Revisi')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\Select::make('tanggungan_type')
                        ->label('Kategori Tanggungan')
                        ->options([
                            'Merusak Barang / Inventaris' => 'Merusak Barang / Inventaris',
                            'Tugas Belum Selesai' => 'Tugas Belum Selesai',
                            'Dokumen Tidak Lengkap' => 'Dokumen Tidak Lengkap',
                            'Lainnya' => 'Lainnya',
                        ])
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('admin_note')
                        ->label('Catatan Admin / Revisi')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => 'rejected',
                        'tanggungan_type' => $data['tanggungan_type'],
                        'admin_note' => $data['admin_note'],
                    ]);
                    \Filament\Notifications\Notification::make()->title('Laporan ditolak')->success()->send();
                })
                ->visible(fn () => $this->record->status !== 'rejected'),
            \Filament\Actions\Action::make('upload_certificate')
                ->label('Upload Sertifikat')
                ->icon('heroicon-o-academic-cap')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->label('Judul / Keterangan')
                        ->required()
                        ->maxLength(255)
                        ->default('Sertifikat Praktik Kerja Lapangan'),
                    \Filament\Forms\Components\FileUpload::make('file_path')
                        ->label('File Sertifikat')
                        ->disk('public')
                        ->directory('certificates')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    \App\Models\Certificate::create([
                        'user_id' => $this->record->user_id,
                        'title' => $data['title'],
                        'file_path' => $data['file_path'],
                    ]);
                    \Filament\Notifications\Notification::make()->title('Sertifikat berhasil diunggah')->success()->send();
                })
                ->visible(fn () => $this->record->status === 'approved'),
        ];
    }
}
