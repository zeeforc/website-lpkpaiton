<?php

namespace App\Filament\Resources\Kurikulums\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KurikulumsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_kurikulum')
                    ->label('Gambar Kurikulum')
                    ->disk('public'),
                TextColumn::make('kurikulum_title')
                    ->label('Nama Kurikulum')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('desk_kurikulum')
                    ->label('Deskripsi Kurikulum')
                    ->limit(50),
                TextColumn::make('desk_singkat')
                    ->label('Deskripsi Singkat Kurikulum')
                    ->limit(50),
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
