<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Schemas;

use Filament\Schemas\Schema;

class ReportSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Siswa')
                    ->required()
                    ->disabled(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('Judul Laporan')
                    ->required()
                    ->disabled(),
                \Filament\Forms\Components\Textarea::make('notes')
                    ->label('Catatan Siswa')
                    ->disabled(),
                \Filament\Forms\Components\FileUpload::make('file_path')
                    ->label('File Laporan')
                    ->directory('laporan')
                    ->acceptedFileTypes(['application/pdf'])
                    ->disabled(),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status Laporan')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('admin_note')
                    ->label('Catatan Admin / Revisi'),
            ]);
    }
}
