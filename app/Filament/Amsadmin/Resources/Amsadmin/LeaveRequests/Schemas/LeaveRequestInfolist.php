<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\LeaveRequests\Schemas;

use Filament\Schemas\Schema;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Izin Siswa')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('user.name')->label('Siswa'),
                            TextEntry::make('type')->label('Tipe')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'sakit' => 'warning',
                                    'izin' => 'info',
                                    default => 'gray',
                                }),
                            TextEntry::make('date')->label('Tanggal Mulai Izin')->date('d/m/Y'),
                            TextEntry::make('end_date')->label('Tanggal Selesai Izin')->date('d/m/Y')->placeholder('-'),
                            TextEntry::make('reason')->label('Alasan Lengkap')->columnSpanFull(),
                            TextEntry::make('status')->label('Status Persetujuan')->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'warning',
                                }),
                            TextEntry::make('admin_notes')->label('Catatan Admin')->columnSpanFull()->placeholder('-'),
                        ])
                    ]),
                Section::make('Dokumen Pendukung')
                    ->schema([
                        TextEntry::make('attachment_path')
                            ->label('Nama File (Bukti)')
                            ->formatStateUsing(fn ($state) => $state ? basename($state) : 'Tidak ada lampiran')
                            ->url(fn ($record) => $record->attachment_path ? asset('storage/' . $record->attachment_path) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-document-text')
                            ->color('primary')
                    ])
            ]);
    }
}
