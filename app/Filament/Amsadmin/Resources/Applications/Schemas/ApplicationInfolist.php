<?php

namespace App\Filament\Amsadmin\Resources\Applications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pribadi & Akademik')
                    ->description('Detail pendaftar dan informasi instansi pendidikan.')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            \Filament\Infolists\Components\TextEntry::make('nama_lengkap')->label('Nama Lengkap')
                                ->icon('heroicon-m-user'),
                            \Filament\Infolists\Components\TextEntry::make('no_hp')->label('No Handphone')
                                ->icon('heroicon-m-phone'),
                            \Filament\Infolists\Components\TextEntry::make('tingkat_pendidikan')->label('Tingkat Pendidikan')
                                ->badge()
                                ->color(fn ($state) => $state === 'Mahasiswa' ? 'primary' : 'warning')
                                ->icon('heroicon-m-academic-cap'),
                            \Filament\Infolists\Components\TextEntry::make('instansi')->label('Instansi / Perguruan Tinggi')
                                ->icon('heroicon-m-building-office-2'),
                            \Filament\Infolists\Components\TextEntry::make('jurusan')->label('Jurusan / Bidang Studi')
                                ->icon('heroicon-m-book-open'),
                        ])
                    ])->collapsible(),

                \Filament\Schemas\Components\Section::make('Detail Pengajuan')
                    ->description('Informasi terkait rencana pelaksanaan PKL.')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            \Filament\Infolists\Components\TextEntry::make('pengajuan')->label('Pengajuan')
                                ->badge()
                                ->color('info'),
                            \Filament\Infolists\Components\TextEntry::make('periode_gelombang')->label('Periode Gelombang')
                                ->icon('heroicon-m-calendar-days'),
                            \Filament\Infolists\Components\TextEntry::make('jumlah_peserta')->label('Jumlah Peserta')
                                ->icon('heroicon-m-users'),
                            \Filament\Infolists\Components\TextEntry::make('lama_durasi_bulan')->label('Lama Durasi (Bulan)')
                                ->icon('heroicon-m-clock'),
                            \Filament\Infolists\Components\TextEntry::make('fokus_studi')->label('Ringkasan Fokus Studi')
                                ->columnSpanFull(),
                            \Filament\Infolists\Components\TextEntry::make('email_balasan')->label('Email Surat Balasan')
                                ->icon('heroicon-m-envelope'),
                            \Filament\Infolists\Components\TextEntry::make('created_at')->label('Tanggal Pendaftaran')
                                ->dateTime('d M Y, H:i')
                                ->icon('heroicon-m-calendar'),
                        ])
                    ])->collapsible(),
                
                \Filament\Schemas\Components\Section::make('Dokumen Persyaratan')
                    ->description('Surat pengantar resmi dan proposal PKL yang dilampirkan.')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('documents')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('original_name')
                                    ->label('Nama File')
                                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                                    ->openUrlInNewTab()
                                    ->icon('heroicon-m-document-text'),
                            ])
                            ->grid(2)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }
}
