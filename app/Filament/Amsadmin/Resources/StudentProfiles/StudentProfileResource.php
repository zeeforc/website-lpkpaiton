<?php

namespace App\Filament\Amsadmin\Resources\StudentProfiles;

use App\Filament\Amsadmin\Resources\StudentProfiles\Pages\CreateStudentProfile;
use App\Filament\Amsadmin\Resources\StudentProfiles\Pages\EditStudentProfile;
use App\Filament\Amsadmin\Resources\StudentProfiles\Pages\ListStudentProfiles;
use App\Filament\Amsadmin\Resources\StudentProfiles\Schemas\StudentProfileForm;
use App\Filament\Amsadmin\Resources\StudentProfiles\Tables\StudentProfilesTable;
use App\Models\StudentProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentProfileResource extends Resource
{
    protected static ?string $model = StudentProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Data Siswa';
    
    protected static ?string $modelLabel = 'Profil Siswa';
    
    protected static ?string $pluralModelLabel = 'Data Siswa';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return StudentProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentProfilesTable::configure($table);
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
            'index' => ListStudentProfiles::route('/'),
            'create' => CreateStudentProfile::route('/create'),
            'edit' => EditStudentProfile::route('/{record}/edit'),
        ];
    }
}
