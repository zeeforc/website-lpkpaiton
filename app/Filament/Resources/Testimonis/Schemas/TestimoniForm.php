<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimoniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('role'),
                TextInput::make('company'),
                FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('testimoni-photos'),
                Textarea::make('quote')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
