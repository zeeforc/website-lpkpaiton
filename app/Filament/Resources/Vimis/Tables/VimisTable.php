<?php

namespace App\Filament\Resources\Vimis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VimisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visi_title')
                    ->label('Judul Visi')
                    ->limit(50),
                TextColumn::make('misi_title')
                    ->label('Judul Misi')
                    ->limit(50),
                TextColumn::make('visi_text')
                    ->label('Text Visi')
                    ->limit(50)
                    ->alignCenter(),
                TextColumn::make('misi_text')
                    ->label('Text Misi')
                    ->limit(50)
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
