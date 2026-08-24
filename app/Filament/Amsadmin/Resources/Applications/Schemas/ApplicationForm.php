<?php

namespace App\Filament\Amsadmin\Resources\Applications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
            ]);
    }
}
