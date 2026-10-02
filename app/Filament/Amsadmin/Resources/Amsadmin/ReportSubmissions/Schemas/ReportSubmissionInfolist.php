<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\ReportSubmissions\Schemas;

use Filament\Schemas\Schema;

class ReportSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Laporan')
                    ->description('Informasi lengkap mengenai laporan PKL siswa')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            \Filament\Infolists\Components\TextEntry::make('user.name')
                                ->label('Nama Siswa'),
                            \Filament\Infolists\Components\TextEntry::make('title')
                                ->label('Judul Laporan'),
                            \Filament\Infolists\Components\TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                }),
                            \Filament\Infolists\Components\TextEntry::make('file_path')
                                ->label('File Laporan')
                                ->formatStateUsing(fn () => 'Unduh / Lihat File')
                                ->icon('heroicon-m-arrow-down-tray')
                                ->color('primary')
                                ->url(fn ($record) => asset('storage/' . $record->file_path))
                                ->openUrlInNewTab(),
                        ]),
                        \Filament\Schemas\Components\Grid::make(1)->schema([
                            \Filament\Infolists\Components\TextEntry::make('notes')
                                ->label('Catatan Siswa'),
                            \Filament\Infolists\Components\TextEntry::make('admin_note')
                                ->label('Catatan Admin / Revisi'),
                        ]),
                    ]),
            ]);
    }
}
