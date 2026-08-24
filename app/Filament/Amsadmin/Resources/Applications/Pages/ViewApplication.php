<?php

namespace App\Filament\Amsadmin\Resources\Applications\Pages;

use App\Filament\Amsadmin\Resources\Applications\ApplicationResource;
use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('permohonan_diterima')
                ->label('Permohonan Diterima')
                ->color('primary')
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Permohonan Diterima')
                ->modalDescription('Apakah Anda yakin ingin menyetujui permohonan awal ini? Sistem akan mengirim email ke pendaftar untuk mengunggah dokumen.')
                ->modalSubmitActionLabel('Ya, Setujui Permohonan')
                ->action(function () {
                    $this->record->update(['status' => 'permohonan_diterima']);
                    Notification::make()
                        ->title('Permohonan berhasil diterima. Email dikirim ke pendaftar.')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->status === 'pending'),

            Actions\Action::make('lolos')
                ->label('Lolos')
                ->color('success')
                ->icon('heroicon-m-check-badge')
                ->requiresConfirmation()
                ->modalHeading('Terima / Loloskan Pendaftar')
                ->modalDescription('Pendaftar ini akan berstatus Lolos. Email notifikasi akan otomatis dikirim berdasarkan tingkat pendidikannya.')
                ->modalSubmitActionLabel('Ya, Loloskan')
                ->action(function () {
                    $this->record->update(['status' => 'accepted']);
                    Notification::make()
                        ->title('Pendaftar berhasil diloloskan')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->status === 'document_review'),

            Actions\Action::make('tolak')
                ->label('Tolak / Revisi')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->form([
                    \Filament\Forms\Components\Textarea::make('note')
                        ->label('Catatan Penolakan / Revisi')
                        ->required()
                        ->placeholder('Tuliskan alasan penolakan atau instruksi revisi di sini...'),
                ])
                ->action(function (array $data) {
                    $this->record->update(['status' => 'rejected']);
                    
                    \App\Models\ApplicationNote::create([
                        'application_id' => $this->record->id,
                        'user_id' => auth()->id(),
                        'note' => $data['note'],
                    ]);

                    Notification::make()
                        ->title('Pendaftaran berhasil ditolak / direvisi')
                        ->success()
                        ->send();
                })
                ->visible(fn () => in_array($this->record->status, ['pending', 'document_review'])),
        ];
    }
}
