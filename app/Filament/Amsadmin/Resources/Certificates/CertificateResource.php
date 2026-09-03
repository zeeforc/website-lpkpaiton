<?php

namespace App\Filament\Amsadmin\Resources\Certificates;

use App\Filament\Amsadmin\Resources\Certificates\Pages\CreateCertificate;
use App\Filament\Amsadmin\Resources\Certificates\Pages\EditCertificate;
use App\Filament\Amsadmin\Resources\Certificates\Pages\ListCertificates;
use App\Filament\Amsadmin\Resources\Certificates\Schemas\CertificateForm;
use App\Filament\Amsadmin\Resources\Certificates\Tables\CertificatesTable;
use App\Models\Certificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Sertifikat PKL';
    
    protected static ?string $modelLabel = 'Sertifikat PKL';
    
    protected static ?string $pluralModelLabel = 'Sertifikat PKL';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CertificateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
            'create' => CreateCertificate::route('/create'),
            'edit' => EditCertificate::route('/{record}/edit'),
        ];
    }
}
