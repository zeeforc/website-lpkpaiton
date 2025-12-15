<?php

namespace App\Filament\Resources\Homes;

use App\Filament\Resources\Homes\Pages\CreateHome;
use App\Filament\Resources\Homes\Pages\EditHome;
use App\Filament\Resources\Homes\Pages\ListHomes;
use App\Filament\Resources\Homes\Schemas\HomeForm;
use App\Filament\Resources\Homes\Tables\HomesTable;
use App\Models\Home;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomeResource extends Resource
{
    protected static ?string $model = Home::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string | UnitEnum | null $navigationGroup = 'Home Management';

    protected static ?string $recordTitleAttribute = 'Home';

    protected static ?string $navigationLabel = 'Home';

    public static function getPluralLabel(): ?string
    {
        return 'Home';
    }

    public static function getLabel(): ?string
    {
        return 'Home';
    }

    public static function form(Schema $schema): Schema
    {
        return HomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomesTable::configure($table);
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
            'index' => ListHomes::route('/'),
            'create' => CreateHome::route('/create'),
            'edit' => EditHome::route('/{record}/edit'),
        ];
    }
}
