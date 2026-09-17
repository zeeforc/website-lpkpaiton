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
                    ->label('File Format')
                    ->directory('clearance_templates')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
