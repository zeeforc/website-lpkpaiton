<?php

namespace App\Filament\Amsadmin\Resources\ClearanceSubmissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClearanceSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('file_path')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'])
                    ->default('pending')
                    ->required(),
                Textarea::make('admin_note')
                    ->columnSpanFull(),
            ]);
    }
}
