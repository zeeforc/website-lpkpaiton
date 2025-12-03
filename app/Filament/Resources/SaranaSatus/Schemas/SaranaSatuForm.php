<?php

namespace App\Filament\Resources\SaranaSatus\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class SaranaSatuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Textarea::make('sarana_desk')
                            ->label('Deskripsi Sarana')
                            ->placeholder('Sampai saat...')
                            ->rows(5)
                            ->required(),
                    ]),
            ]);
    }
}
