<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Schemas;

use Filament\Schemas\Schema;

class ReportSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\TextEntry::make('user.name')
                    ->label('Nama Siswa'),
                \Filament\Infolists\Components\TextEntry::make('title')
                    ->label('Judul Laporan'),
                \Filament\Infolists\Components\TextEntry::make('notes')
                    ->label('Catatan Siswa'),
                \Filament\Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                \Filament\Infolists\Components\TextEntry::make('file_path')
                    ->label('File Laporan')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                \Filament\Infolists\Components\TextEntry::make('admin_note')
                    ->label('Catatan Admin / Revisi'),
            ]);
    }
}
