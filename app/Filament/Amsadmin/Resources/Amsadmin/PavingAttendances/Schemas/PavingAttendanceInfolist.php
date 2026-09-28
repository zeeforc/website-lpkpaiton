<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingAttendances\Schemas;

use Filament\Schemas\Schema;

class PavingAttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Absensi')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('user.name')->label('Nama Karyawan'),
                        \Filament\Infolists\Components\TextEntry::make('date')->label('Tanggal')->date(),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Hadir' => 'success',
                                'Telat' => 'warning',
                                'Tidak Hadir' => 'danger',
                                'Izin' => 'warning',
                                'Sakit' => 'info',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('check_in')->label('Jam Masuk')->time('H:i'),
                        \Filament\Infolists\Components\TextEntry::make('check_out')->label('Jam Pulang')->time('H:i'),
                        \Filament\Infolists\Components\TextEntry::make('notes')->label('Catatan')->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('work_description')->label('Deskripsi Pekerjaan')->columnSpanFull(),
                    ])->columns(2),
                \Filament\Schemas\Components\Section::make('Foto Absensi & Kegiatan')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('photo_path')
                            ->label('Foto Saat Hadir (Check-In)')
                            ->disk('public')
                            ->width('100%')
                            ->height('auto')
                            ->extraImgAttributes(['style' => 'max-width: 400px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);'])
                            ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=Expired&color=FFFFFF&background=ef4444'),
                            
                        \Filament\Infolists\Components\ImageEntry::make('checkout_photos')
                            ->label('Dokumentasi Kegiatan (Check-Out)')
                            ->getStateUsing(fn ($record) => $record->checkout_photos ? (is_array($record->checkout_photos) ? $record->checkout_photos : json_decode($record->checkout_photos, true)) : null)
                            ->disk('public')
                            ->width('100%')
                            ->height('auto')
                            ->extraImgAttributes(['style' => 'max-width: 300px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);'])
                    ])->columns(2)->collapsible(),
            ]);
    }
}
