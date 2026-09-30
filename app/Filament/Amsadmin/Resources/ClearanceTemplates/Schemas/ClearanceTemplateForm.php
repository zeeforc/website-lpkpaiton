<?php

namespace App\Filament\Amsadmin\Resources\ClearanceTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClearanceTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Format')
                    ->required(),
                FileUpload::make('file_path')
                    ->label('File Format (PDF/Word/Image)')
                    ->directory('clearance_templates')
                    ->rules(['mimes:pdf,doc,docx,jpeg,png,jpg'])
                    ->maxSize(5120)
                    ->downloadable()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
