<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\PavingLeaveRequests\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class PavingLeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Siswa')
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal Izin/Sakit')
                    ->required(),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                    ])
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan Lengkap')
                    ->required(),
                FileUpload::make('attachment_path')
                    ->label('Bukti (Surat Dokter/Kampus)')
                    ->directory('leave_attachments')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png']),
                Select::make('status')
                    ->label('Status Persetujuan')
                    ->options([
                        'pending' => 'Menunggu (Pending)',
                        'approved' => 'Disetujui (Approved)',
                        'rejected' => 'Ditolak (Rejected)',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('admin_notes')
                    ->label('Catatan Admin (opsional)')
            ]);
    }
}
