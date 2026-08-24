<?php

namespace App\Filament\Resources\BeritaUtamas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor; // Import RichEditor
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
                            ->label('Foto Berita (Utama)')
                            ->image()
                            ->directory('berita')
                            ->disk('public')
                            ->visibility('public')
                            ->required(),

                        TextInput::make('berita_utama_title')
                            ->label('Judul Berita / Kegiatan')
                            ->required(),

                        // RichEditor dengan dukungan Alignment dan Image Upload
                        RichEditor::make('berita_utama_desk')
                            ->label('Isi Berita / Kegiatan')
                            ->helperText('Gunakan tombol alignment (Kiri/Kanan) pada gambar agar teks membungkus gambar (Text Wrap).')
                            ->fileAttachmentsDirectory('berita/attachments')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->toolbarButtons([
                                'attachFiles',
                                'bold',
                                'italic',
                                'link',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'alignCenter',
                            ])
                            ->columnSpanFull()
                            ->required(),

                        DateTimePicker::make('tgl_berita')
                            ->label('Tanggal Berita')
                            ->default(Date::now())
                            ->required(),
                    ]),
            ]);
    }
}
