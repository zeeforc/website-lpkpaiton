<?php

namespace App\Filament\Amsadmin\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->disabled(),
                TextInput::make('name')
                    ->disabled(),
                Textarea::make('value')
                    ->columnSpanFull()
                    ->visible(fn (callable $get) => $get('type') !== 'file'),
                FileUpload::make('value')
                    ->label('File')
                    ->directory('settings')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(5120)
                    ->visible(fn (callable $get) => $get('type') === 'file')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('text'),
            ]);
    }
}
