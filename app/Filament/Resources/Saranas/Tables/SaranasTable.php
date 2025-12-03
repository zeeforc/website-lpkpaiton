<?php

namespace App\Filament\Resources\Saranas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaranasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('fasilitas_image')
                    ->label('Gambar Fasilitas')
                    ->disk('public')
                    ->alignCenter()
                    ->width('4rem')
                    ->height('8rem'),
                TextColumn::make('fasilitas_title')
                    ->label('Nama Fasilitas')
                    ->alignCenter(),
                TextColumn::make('fasilitas_desk')
                    ->label('Deskripsi Fasilitas')
                    ->alignCenter()
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
