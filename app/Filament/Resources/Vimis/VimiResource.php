<?php

namespace App\Filament\Resources\Vimis;

use App\Filament\Resources\Vimis\Pages\CreateVimi;
use App\Filament\Resources\Vimis\Pages\EditVimi;
use App\Filament\Resources\Vimis\Pages\ListVimis;
use App\Filament\Resources\Vimis\Schemas\VimiForm;
use App\Filament\Resources\Vimis\Tables\VimisTable;
use App\Models\Vimi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VimiResource extends Resource
{
    protected static ?string $model = Vimi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $recordTitleAttribute = 'Vimi';

    protected static ?string $navigationLabel = 'Visi & Misi';

    public static function getPluralLabel(): ?string
    {
        return 'Visi & Misi';
    }

    public static function getLabel(): ?string
    {
        return 'Visi & Misi';
    }

    public static function form(Schema $schema): Schema
    {
        return VimiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VimisTable::configure($table);
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
            'index' => ListVimis::route('/'),
            'create' => CreateVimi::route('/create'),
            'edit' => EditVimi::route('/{record}/edit'),
        ];
    }
}
