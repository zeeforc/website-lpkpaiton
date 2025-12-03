<?php

namespace App\Filament\Resources\Saranas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaranaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('fasilitas_title')
                            ->label('Nama Fasilitas')
                            ->required(),
                        TextInput::make('fasilitas_desk')
                            ->label('Deskripsi Fasilitas')
                            ->required(),
                        FileUpload::make('fasilitas_image')
                            ->label('Gambar Fasilitas')
                            ->image()
                            ->disk('public')
                            ->directory('sarana')
                            ->required(),
                    ]),
            ]);
    }
}
