<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->directory('teams')    // simpan di storage/app/public/homes
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Strukur')
                            ->required(),
                        TextInput::make('position')
                            ->label('Jabatan Struktur')
                            ->required(),
                    ]),
            ]);
    }
}
