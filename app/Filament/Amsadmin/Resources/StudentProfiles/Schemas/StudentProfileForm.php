<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StudentProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('guru_id')
                    ->label('Guru Pendamping (Pondok)')
                    ->relationship('guru', 'name', fn ($query) => $query->where('role', 'guru_pondok'))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                \Filament\Forms\Components\FileUpload::make('pas_foto')
                    ->image()
                    ->directory('pas_foto')
                    ->disk('public'),
                TextInput::make('nama_panggilan'),
                TextInput::make('nis'),
                TextInput::make('nisn'),
                TextInput::make('jenis_kelamin'),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tanggal_lahir'),
                Textarea::make('alamat_lengkap')
                    ->columnSpanFull(),
                TextInput::make('npsn'),
                TextInput::make('kelas'),
                TextInput::make('tahun_ajaran'),
                TextInput::make('nama_wali_kelas'),
                TextInput::make('no_hp_wali_kelas'),
                TextInput::make('nama_kontak_darurat'),
                TextInput::make('hubungan_kontak_darurat'),
                TextInput::make('no_hp_kontak_darurat'),
                Textarea::make('alamat_kontak_darurat')
                    ->columnSpanFull(),
                TextInput::make('pembimbing_industri_nama'),
                TextInput::make('pembimbing_industri_hp'),
                TextInput::make('guru_pembimbing_nama'),
                TextInput::make('guru_pembimbing_hp'),
            ]);
    }
}
