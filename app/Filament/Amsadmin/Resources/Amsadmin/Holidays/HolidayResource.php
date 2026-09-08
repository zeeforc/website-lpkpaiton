<?php

namespace App\Filament\Amsadmin\Resources\Amsadmin\Holidays;

use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Pages\CreateHoliday;
use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Pages\EditHoliday;
use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Pages\ListHolidays;
use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Schemas\HolidayForm;
use App\Filament\Amsadmin\Resources\Amsadmin\Holidays\Tables\HolidaysTable;
use App\Models\Holiday;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HolidayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HolidaysTable::configure($table);
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
            'index' => ListHolidays::route('/'),
            'create' => CreateHoliday::route('/create'),
            'edit' => EditHoliday::route('/{record}/edit'),
        ];
    }
}
