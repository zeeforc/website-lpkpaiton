<?php

namespace App\Filament\Resources\SaranaSatus;

use App\Filament\Resources\SaranaSatus\Pages\CreateSaranaSatu;
use App\Filament\Resources\SaranaSatus\Pages\EditSaranaSatu;
use App\Filament\Resources\SaranaSatus\Pages\ListSaranaSatus;
use App\Filament\Resources\SaranaSatus\Schemas\SaranaSatuForm;
use App\Filament\Resources\SaranaSatus\Tables\SaranaSatusTable;
use App\Models\SaranaSatu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SaranaSatuResource extends Resource
{
    protected static ?string $model = SaranaSatu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string | UnitEnum | null $navigationGroup = 'Pelatihan dan Sarana Prasarana';

    protected static ?string $navigationLabel = 'Sarana';

    public static function getPluralLabel(): ?string
    {
        return 'Sarana';
    }

    public static function getLabel(): ?string
    {
        return 'Sarana';
    }

    public static function form(Schema $schema): Schema
    {
        return SaranaSatuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaranaSatusTable::configure($table);
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
            'index' => ListSaranaSatus::route('/'),
            'create' => CreateSaranaSatu::route('/create'),
            'edit' => EditSaranaSatu::route('/{record}/edit'),
        ];
    }
}
