<?php

namespace App\Filament\Resources\BeritaUtamas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Date;

class BeritaUtamaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FileUpload::make('berita_utama_image')
                            ->label('Foto Berita')
                            ->image()
                            ->directory('berita')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),
                        TextInput::make('berita_utama_title')
                            ->label('Judul Berita / Kegiatan')
                            ->required(),
                        Textarea::make('berita_utama_desk')
                            ->label('Isi Berita / Kegiatan')
                            ->required(),
                        DateTimePicker::make('tgl_berita')
                            ->label('Tanggal Berita')
                            ->default(Date::now())
                            ->required(),
                    ]),
            ]);
    }
}
