<?php

namespace App\Filament\Amsadmin\Resources\PklLetterTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PklLetterTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Surat')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'surat_balasan' => 'Surat Balasan',
                        'surat_perjanjian' => 'Surat Perjanjian PKL',
                        default => $state ?? '-',
                    })
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'surat_balasan' => 'success',
                        'surat_perjanjian' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
