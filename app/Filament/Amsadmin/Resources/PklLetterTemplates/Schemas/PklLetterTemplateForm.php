<?php

namespace App\Filament\Amsadmin\Resources\PklLetterTemplates\Schemas;

use Filament\Schemas\Schema;

class PklLetterTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Nama Template (Bebas)')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('type')
                    ->label('Jenis Surat')
                    ->options([
                        'surat_balasan' => 'Surat Balasan',
                        'surat_perjanjian' => 'Surat Perjanjian PKL',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\FileUpload::make('file_path')
                    ->label('File Template (HANYA .docx)')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->disk('public')
                    ->directory('pkl-templates')
                    ->required(),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->label('Aktif?')
                    ->default(true),
            ]);
    }
}
