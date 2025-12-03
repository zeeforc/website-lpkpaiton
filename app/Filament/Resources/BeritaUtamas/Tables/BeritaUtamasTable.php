<?php

namespace App\Filament\Resources\BeritaUtamas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BeritaUtamasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('berita_utama_image')
                    ->label('Foto Berita')
                    ->disk('public')
                    ->rounded()
                    ->size(50),
                TextColumn::make('berita_utama_title')
                    ->label('Judul Berita / Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                TextColumn::make('berita_utama_desk')
                    ->label('Isi Berita / Kegiatan')
                    ->limit(30),
                TextColumn::make('tgl_berita')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
