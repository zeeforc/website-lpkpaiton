<?php

namespace App\Filament\Resources\Pelatihans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PelatihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FileUpload::make('image_pelatihan')
                            ->label('Foto Pelatihan')
                            ->image()
                            ->directory('pelatihan')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('nama_pelatihan')
                            ->label('Judul Pelatihan')
                            ->required(),
                        Textarea::make('deskripsi_pelatihan')
                            ->label('Deskripsi Pelatihan')
                            ->required(),
                        TextInput::make('desk_singkat')
                            ->label('Fokus Pelatihan')
                            ->required(),
                    ]),
            ]);
    }
}
