<?php

namespace App\Filament\Resources\Galeries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GaleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FileUpload::make('galery_image')
                            ->label('Foto Galeri')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('galeries')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required(),
                    ]),
            ]);
    }
}
