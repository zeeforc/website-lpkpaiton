<?php

namespace App\Filament\Resources\Vimis\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VimiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('visi_title')
                            ->label('Judul Visi')
                            ->placeholder('Visi Kami')
                            ->required(),
                        Textarea::make('visi_text')
                            ->label('Teks Visi')
                            ->placeholder('Penyelenggaraan Pelatihan...')
                            ->rows(5)
                            ->required(),
                        TextInput::make('misi_title')
                            ->label('Judul Misi')
                            ->placeholder('Misi Kami')
                            ->required(),
                        Textarea::make('misi_text')
                            ->label('Teks Misi')
                            ->placeholder('Penyelenggaraan Pelatihan...')
                            ->rows(5)
                            ->required(),
                    ]),
            ]);
    }
}
