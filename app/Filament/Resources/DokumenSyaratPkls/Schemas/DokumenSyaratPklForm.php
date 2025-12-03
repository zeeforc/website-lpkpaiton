<?php

namespace App\Filament\Resources\DokumenSyaratPkls\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DokumenSyaratPklForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('dokumen_syarat_pkl_title')
                            ->label('Nama Dokumen Syarat PKL')
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('Dokumen Syarat PKL')
                            ->disk('public')
                            ->directory('dokumen_syarat_pkl')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10048)
                            ->required(),
                    ]),
            ]);
    }
}
