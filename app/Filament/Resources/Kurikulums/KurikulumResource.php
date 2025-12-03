<?php

namespace App\Filament\Resources\Kurikulums;

use App\Filament\Resources\Kurikulums\Pages\CreateKurikulum;
use App\Filament\Resources\Kurikulums\Pages\EditKurikulum;
use App\Filament\Resources\Kurikulums\Pages\ListKurikulums;
use App\Filament\Resources\Kurikulums\Schemas\KurikulumForm;
use App\Filament\Resources\Kurikulums\Tables\KurikulumsTable;
use App\Models\Kurikulum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KurikulumResource extends Resource
{
    protected static ?string $model = Kurikulum::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'Kurikulum';

    protected static ?string $navigationLabel = 'Kurikulum';

    public static function getPluralLabel(): ?string
    {
        return 'Kurikulum';
    }

    public static function getLabel(): ?string
    {
        return 'Kurikulum';
    }

    public static function form(Schema $schema): Schema
    {
        return KurikulumForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KurikulumsTable::configure($table);
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
            'index' => ListKurikulums::route('/'),
            'create' => CreateKurikulum::route('/create'),
            'edit' => EditKurikulum::route('/{record}/edit'),
        ];
    }
}
