<?php

namespace App\Filament\Amsadmin\Resources\ClearanceTemplates;

use App\Filament\Amsadmin\Resources\ClearanceTemplates\Pages\CreateClearanceTemplate;
use App\Filament\Amsadmin\Resources\ClearanceTemplates\Pages\EditClearanceTemplate;
use App\Filament\Amsadmin\Resources\ClearanceTemplates\Pages\ListClearanceTemplates;
use App\Filament\Amsadmin\Resources\ClearanceTemplates\Schemas\ClearanceTemplateForm;
use App\Filament\Amsadmin\Resources\ClearanceTemplates\Tables\ClearanceTemplatesTable;
use App\Models\ClearanceTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClearanceTemplateResource extends Resource
{
    protected static ?string $model = ClearanceTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Templat Surat Bebas Tanggungan';
    protected static string | \UnitEnum | null $navigationGroup = 'Siswa';

    public static function form(Schema $schema): Schema
    {
        return ClearanceTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClearanceTemplatesTable::configure($table);
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
            'index' => ListClearanceTemplates::route('/'),
            'create' => CreateClearanceTemplate::route('/create'),
            'edit' => EditClearanceTemplate::route('/{record}/edit'),
        ];
    }
}
