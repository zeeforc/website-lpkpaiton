<?php

namespace App\Filament\Resources\PklStats;

use App\Filament\Resources\PklStats\PklStatResource\Pages;
use App\Models\PklStat;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PklStatResource extends Resource
{
    protected static ?string $model = PklStat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Data Peserta PKL';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Section::make('Ringkasan Data Peserta PKL')
                ->description('Data ini ditampilkan di halaman Berita sebagai ringkasan statistik peserta PKL.')
                ->schema([
                    TextInput::make('total_peserta')
                        ->label('Total Peserta PKL')
                        ->helperText('Jumlah total peserta yang pernah mengikuti PKL di LPK Paiton Selaras.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    TextInput::make('peserta_aktif')
                        ->label('Peserta PKL Saat Ini')
                        ->helperText('Jumlah peserta yang sedang aktif PKL.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    TextInput::make('jumlah_jurusan')
                        ->label('Jumlah Jurusan')
                        ->helperText('Jumlah jurusan dari para peserta PKL.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    TextInput::make('jumlah_sekolah')
                        ->label('Jumlah Sekolah / Kampus')
                        ->helperText('Jumlah asal sekolah atau kampus peserta.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    TextInput::make('jumlah_program')
                        ->label('Jumlah Program PKL')
                        ->helperText('Jumlah program PKL yang tersedia.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('total_peserta')
                    ->label('Total Peserta')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('peserta_aktif')
                    ->label('Peserta Aktif')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('jumlah_jurusan')
                    ->label('Jurusan')
                    ->numeric(),

                TextColumn::make('jumlah_sekolah')
                    ->label('Sekolah / Kampus')
                    ->numeric(),

                TextColumn::make('jumlah_program')
                    ->label('Program')
                    ->numeric(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPklStats::route('/'),
            'edit'  => Pages\EditPklStat::route('/{record}/edit'),
        ];
    }
}
