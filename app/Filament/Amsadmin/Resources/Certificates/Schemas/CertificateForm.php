<?php

namespace App\Filament\Amsadmin\Resources\Certificates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\User $record) => "{$record->name} ({$record->email})")
                    ->label('Siswa')
                    ->searchable(['name', 'email'])
                    ->required(),
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
            ]);
    }
}
