<?php

namespace App\Filament\Amsadmin\Resources\Settings;

use App\Filament\Amsadmin\Resources\Settings\Pages\CreateSetting;
use App\Filament\Amsadmin\Resources\Settings\Pages\EditSetting;
use App\Filament\Amsadmin\Resources\Settings\Pages\ListSettings;
use App\Filament\Amsadmin\Resources\Settings\Schemas\SettingForm;
use App\Filament\Amsadmin\Resources\Settings\Tables\SettingsTable;
use App\Models\Setting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingsTable::configure($table);
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
            'index' => ListSettings::route('/'),
            'edit' => EditSetting::route('/{record}/edit'),
        ];
    }
}
