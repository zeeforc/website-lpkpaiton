<?php

namespace App\Filament\Resources\Homes\Schemas;

use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Home')
                            ->required(),
                        FileUpload::make('bg_image')
                            ->label('Bakcground Image Home')
                            ->image()
                            ->directory('homes')    // simpan di storage/app/public/homes
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required(),
                    ]),
            ]);
    }
}
