<?php

namespace App\Filament\Amsadmin\Resources\PklLetterTemplates;

use App\Filament\Amsadmin\Resources\PklLetterTemplates\Pages\CreatePklLetterTemplate;
use App\Filament\Amsadmin\Resources\PklLetterTemplates\Pages\EditPklLetterTemplate;
use App\Filament\Amsadmin\Resources\PklLetterTemplates\Pages\ListPklLetterTemplates;
use App\Filament\Amsadmin\Resources\PklLetterTemplates\Schemas\PklLetterTemplateForm;
use App\Filament\Amsadmin\Resources\PklLetterTemplates\Tables\PklLetterTemplatesTable;
use App\Models\PklLetterTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PklLetterTemplateResource extends Resource
{
    protected static ?string $model = PklLetterTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Templat Surat PKL';
    protected static string | \UnitEnum | null $navigationGroup = 'Siswa';
    protected static ?string $modelLabel = 'Templat Surat PKL';

    public static function form(Schema $schema): Schema
    {
        return PklLetterTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PklLetterTemplatesTable::configure($table);
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
            'index' => ListPklLetterTemplates::route('/'),
            'create' => CreatePklLetterTemplate::route('/create'),
            'edit' => EditPklLetterTemplate::route('/{record}/edit'),
        ];
    }
}
