<?php

namespace App\Filament\Amsadmin\Resources\Applications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nama_lengkap')
                    ->required(),
                TextInput::make('instansi')
                    ->required(),
                TextInput::make('jurusan')
                    ->required(),
                TextInput::make('no_hp')
                    ->required(),
                TextInput::make('pengajuan')
                    ->required(),
                TextInput::make('periode_gelombang')
                    ->required(),
                TextInput::make('jumlah_peserta')
                    ->required(),
                TextInput::make('lama_durasi_bulan')
                    ->required()
                    ->numeric(),
                Textarea::make('fokus_studi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('email_balasan')
                    ->email()
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai PKL'),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai PKL'),
                Toggle::make('is_jalur_khusus')
                    ->label('Jalur Khusus (Tidak terhitung kuota)')
                    ->default(false),
                    
                Section::make('Validasi Dokumen')
                    ->description('Tinjau dan berikan status serta catatan revisi pada dokumen pendaftar.')
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->schema([
                                TextInput::make('original_name')
                                    ->label('Nama File')
                                    ->disabled()
                                    ->columnSpan(2),
                                Select::make('status')
                                    ->label('Status Validasi')
                                    ->options([
                                        'Menunggu Review' => 'Menunggu Review',
                                        'Valid' => 'Valid',
                                        'Revisi' => 'Revisi',
                                    ])
                                    ->required()
                                    ->default('Menunggu Review')
                                    ->columnSpan(1),
                                Textarea::make('keterangan')
                                    ->label('Catatan Revisi')
                                    ->placeholder('Berikan keterangan bagian mana yang salah jika statusnya Revisi...')
                                    ->columnSpan(3),
                            ])
                            ->columns(3)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement(),
                    ])->collapsible(),
            ]);
    }
}
