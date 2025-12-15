<?php

namespace App\Filament\Resources\BeritaUtamas;

use App\Filament\Resources\BeritaUtamas\Pages\CreateBeritaUtama;
use App\Filament\Resources\BeritaUtamas\Pages\EditBeritaUtama;
use App\Filament\Resources\BeritaUtamas\Pages\ListBeritaUtamas;
use App\Filament\Resources\BeritaUtamas\Schemas\BeritaUtamaForm;
use App\Filament\Resources\BeritaUtamas\Tables\BeritaUtamasTable;
use App\Models\BeritaUtama;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BeritaUtamaResource extends Resource
{
    protected static ?string $model = BeritaUtama::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string | UnitEnum | null $navigationGroup = 'Acara dan Bantuan';

    protected static ?string $recordTitleAttribute = 'BeritaUtama';

    protected static ?string $navigationLabel = 'Berita';

    public static function getPluralLabel(): ?string
    {
        return 'Berita';
    }

    public static function getLabel(): ?string
    {
        return 'Berita';
    }

    public static function form(Schema $schema): Schema
    {
        return BeritaUtamaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeritaUtamasTable::configure($table);
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
            'index' => ListBeritaUtamas::route('/'),
            'create' => CreateBeritaUtama::route('/create'),
            'edit' => EditBeritaUtama::route('/{record}/edit'),
        ];
    }
}
