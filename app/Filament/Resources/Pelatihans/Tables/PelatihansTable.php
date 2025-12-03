<?php

namespace App\Filament\Resources\Pelatihans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PelatihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_pelatihan')
                ->label('Foto Pelatihan')
                ->disk('public')
                ->rounded()
                ->size(50),
            TextColumn::make('nama_pelatihan')
                ->label('Nama Pelatihan')
                ->searchable()
                ->sortable()
                ->limit(20),
            TextColumn::make('deskripsi_pelatihan')
                ->label('Deskripsi Pelatihan')
                ->limit(30),
            TextColumn::make('desk_singkat')
                ->label('Fokus Pelatihan')
                ->limit(20)
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
