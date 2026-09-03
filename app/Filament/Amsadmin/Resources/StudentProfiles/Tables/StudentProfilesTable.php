<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\ImageColumn::make('pas_foto')
                    ->label('Pas Foto')
                    ->disk('public'),
                TextColumn::make('nama_panggilan')
                    ->searchable(),
                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('npsn')
                    ->searchable(),
                TextColumn::make('kelas')
                    ->searchable(),
                TextColumn::make('tahun_ajaran')
                    ->searchable(),
                TextColumn::make('nama_wali_kelas')
                    ->searchable(),
                TextColumn::make('no_hp_wali_kelas')
                    ->searchable(),
                TextColumn::make('nama_kontak_darurat')
                    ->searchable(),
                TextColumn::make('hubungan_kontak_darurat')
                    ->searchable(),
                TextColumn::make('no_hp_kontak_darurat')
                    ->searchable(),
                TextColumn::make('pembimbing_industri_nama')
                    ->searchable(),
                TextColumn::make('pembimbing_industri_hp')
                    ->searchable(),
                TextColumn::make('guru_pembimbing_nama')
                    ->searchable(),
                TextColumn::make('guru_pembimbing_hp')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
