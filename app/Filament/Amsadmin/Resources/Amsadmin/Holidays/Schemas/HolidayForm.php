<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                DatePicker::make('date')
                    ->label('Tanggal Libur')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Keterangan Libur (cth: Idul Fitri)')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
