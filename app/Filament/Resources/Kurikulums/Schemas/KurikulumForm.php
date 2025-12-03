<?php

namespace App\Filament\Resources\Kurikulums\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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
                    ]),
            ]);
    }
}
