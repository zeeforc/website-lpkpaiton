<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Attendances\Schemas;

use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TimePicker::make('check_in')
                    ->label('Jam Masuk (Check In)'),
                \Filament\Forms\Components\TimePicker::make('check_out')
                    ->label('Jam Pulang (Check Out)'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Telat' => 'Telat',
                        'Tidak Hadir' => 'Tidak Hadir',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('notes')
                    ->label('Catatan Admin')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
