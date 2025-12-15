<?php

namespace App\Filament\Resources\Saranas;

use App\Filament\Resources\Saranas\Pages\CreateSarana;
use App\Filament\Resources\Saranas\Pages\EditSarana;
use App\Filament\Resources\Saranas\Pages\ListSaranas;
use App\Filament\Resources\Saranas\Schemas\SaranaForm;
use App\Filament\Resources\Saranas\Tables\SaranasTable;
use App\Models\Sarana;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SaranaResource extends Resource
{
    protected static ?string $model = Sarana::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    protected static string | UnitEnum | null $navigationGroup = 'Pelatihan dan Sarana Prasarana';

    protected static ?string $recordTitleAttribute = 'Sarana';

    protected static ?string $navigationLabel = 'Prasarana';

    public static function getPluralLabel(): ?string
    {
        return 'Prasarana';
    }

    public static function getLabel(): ?string
    {
        return 'Prasarana';
    }

    public static function form(Schema $schema): Schema
    {
        return SaranaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaranasTable::configure($table);
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
            'index' => ListSaranas::route('/'),
            'create' => CreateSarana::route('/create'),
            'edit' => EditSarana::route('/{record}/edit'),
        ];
    }
}
