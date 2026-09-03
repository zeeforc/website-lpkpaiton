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
                \Filament\Infolists\Components\TextEntry::make('nama_lengkap')->label('Nama Lengkap'),
                \Filament\Infolists\Components\TextEntry::make('no_hp')->label('No Handphone'),
                \Filament\Infolists\Components\TextEntry::make('tingkat_pendidikan')->label('Tingkat Pendidikan')
                    ->badge()
                    ->color(fn ($state) => $state === 'Mahasiswa' ? 'primary' : 'warning'),
                \Filament\Infolists\Components\TextEntry::make('instansi')->label('Instansi / Perguruan Tinggi'),
                \Filament\Infolists\Components\TextEntry::make('jurusan')->label('Jurusan / Bidang Studi'),
                \Filament\Infolists\Components\TextEntry::make('pengajuan')->label('Pengajuan')
                    ->badge()
                    ->color('info'),
                \Filament\Infolists\Components\TextEntry::make('periode_gelombang')->label('Periode Gelombang'),
                \Filament\Infolists\Components\TextEntry::make('jumlah_peserta')->label('Jumlah Peserta'),
                \Filament\Infolists\Components\TextEntry::make('lama_durasi_bulan')->label('Lama Durasi (Bulan)'),
                \Filament\Infolists\Components\TextEntry::make('fokus_studi')->label('Ringkasan Fokus Studi')
                    ->columnSpanFull(),
                \Filament\Infolists\Components\TextEntry::make('email_balasan')->label('Email Surat Balasan')
                    ->icon('heroicon-m-envelope'),
                \Filament\Infolists\Components\TextEntry::make('created_at')->label('Tanggal Pendaftaran')
                    ->dateTime('d M Y, H:i'),
                
                \Filament\Infolists\Components\RepeatableEntry::make('documents')
                    ->label('Surat Pengantar Resmi & Proposal')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('original_name')
                            ->label('Nama File')
                            ->url(fn ($record) => asset('storage/' . $record->file_path))
                            ->openUrlInNewTab(),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),
            ]);
    }
}
