<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class ReportSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->label('Judul Laporan')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Kumpul')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                \Filament\Actions\Action::make('approve')
                    ->label('Terima Laporan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                    })
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'approved'),
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
                    ->action(function (array $data, $record): void {
                        $record->update([
                            'status' => 'rejected',
                            'tanggungan_type' => $data['tanggungan_type'],
                            'admin_note' => $data['admin_note'],
                        ]);
                    })
                    ->visible(fn ($record) => $record->status !== 'rejected'),
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
                    ->action(function (array $data, $record): void {
                        \App\Models\Certificate::create([
                            'user_id' => $record->user_id,
                            'title' => $data['title'],
                            'file_path' => $data['file_path'],
                        ]);
                        \Filament\Notifications\Notification::make()->title('Sertifikat berhasil diunggah')->success()->send();
                    })
                    ->visible(fn ($record) => $record->status === 'approved'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
