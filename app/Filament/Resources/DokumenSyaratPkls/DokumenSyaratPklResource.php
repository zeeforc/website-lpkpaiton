<?php

namespace App\Filament\Resources\DokumenSyaratPkls;

use App\Filament\Resources\DokumenSyaratPkls\Pages\CreateDokumenSyaratPkl;
use App\Filament\Resources\DokumenSyaratPkls\Pages\EditDokumenSyaratPkl;
use App\Filament\Resources\DokumenSyaratPkls\Pages\ListDokumenSyaratPkls;
use App\Filament\Resources\DokumenSyaratPkls\Schemas\DokumenSyaratPklForm;
use App\Filament\Resources\DokumenSyaratPkls\Tables\DokumenSyaratPklsTable;
use App\Models\DokumenSyaratPkl;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DokumenSyaratPklResource extends Resource
{
    protected static ?string $model = DokumenSyaratPkl::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocument;

    protected static string | UnitEnum | null $navigationGroup = 'Acara dan Bantuan';

    protected static ?string $recordTitleAttribute = 'DokumenSyaratPkl';

    protected static ?string $navigationLabel = 'Dokumen Syarat PKL';

    public static function getPluralLabel(): ?string
    {
        return 'Dokumen Syarat PKL';
    }

    public static function getLabel(): ?string
    {
        return 'Dokumen Syarat PKL';
    }

    public static function form(Schema $schema): Schema
    {
        return DokumenSyaratPklForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenSyaratPklsTable::configure($table);
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
            'index' => ListDokumenSyaratPkls::route('/'),
            'create' => CreateDokumenSyaratPkl::route('/create'),
            'edit' => EditDokumenSyaratPkl::route('/{record}/edit'),
        ];
    }
}
