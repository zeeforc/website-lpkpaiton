<?php

namespace App\Filament\Resources\Kurikulums\Schemas;

use App\Services\ExcelToHtml;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KurikulumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FileUpload::make('image_kurikulum')
                            ->label('Gambar Kurikulum')
                            ->image()
                            ->directory('kurikulum')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('kurikulum_title')
                            ->label('Nama Kurikulum')
                            ->required(),
                        Textarea::make('desk_kurikulum')
                            ->label('Deskripsi Kurikulum')
                            ->required(),
                        Textarea::make('desk_singkat')
                            ->label('Deskripsi Singkat Fokus Pembelajaran')
                            ->required(),
                        FileUpload::make('matrix_excel_path')
                            ->label('Upload Matrix Excel')
                            ->disk('public')
                            ->directory('matrix-kurikulum')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                            ])
                            ->columnSpanFull(),

                    ]),
            ]);
    }
}
