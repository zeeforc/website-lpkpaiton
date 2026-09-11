<?php

namespace App\Filament\Amsadmin\Pages;

use App\Models\Setting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Dotswan\MapPicker\Fields\Map;
use Filament\Notifications\Notification;

class PengaturanLokasi extends Page implements HasForms
{
    use InteractsWithForms;

    public static function getNavigationIcon(): string | \Illuminate\View\ComponentAttributeBag | null
    {
        return 'heroicon-o-map-pin';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sistem';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan Lokasi LPK';
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Pengaturan Lokasi LPK';
    }

    public static function getNavigationSort(): ?int
    {
        return 99;
    }

    protected string $view = 'filament.amsadmin.pages.pengaturan-lokasi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'target_lokasi' => 'lpk',
            'absensi_radius' => Setting::where('key', 'absensi_radius')->value('value') ?? '50',
            'location' => [
                'lat' => (float) (Setting::where('key', 'lpk_latitude')->value('value') ?? '-7.7126'),
                'lng' => (float) (Setting::where('key', 'lpk_longitude')->value('value') ?? '113.4687'),
            ]
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pilih Lokasi yang Diatur')
                    ->schema([
                        \Filament\Forms\Components\Select::make('target_lokasi')
                            ->label('Lokasi')
                            ->options([
                                'lpk' => 'LPK Paiton Selaras',
                                'paving' => 'Paving / PLTU Paiton'
                            ])
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state === 'lpk') {
                                    $set('location', [
                                        'lat' => (float) (Setting::where('key', 'lpk_latitude')->value('value') ?? '-7.7126'),
                                        'lng' => (float) (Setting::where('key', 'lpk_longitude')->value('value') ?? '113.4687'),
                                    ]);
                                } else {
                                    $set('location', [
                                        'lat' => (float) (Setting::where('key', 'paving_latitude')->value('value') ?? '-7.7126'),
                                        'lng' => (float) (Setting::where('key', 'paving_longitude')->value('value') ?? '113.4687'),
                                    ]);
                                }
                            })
                            ->required(),
                    ]),

                Section::make('Titik Koordinat')
                    ->description('Cari lokasi menggunakan form search di dalam peta dan geser pin merah ke titik bangunan yang paling tepat.')
                    ->schema([
                        Map::make('location')
                            ->label('Peta Lokasi')
                            ->id('main_map')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -7.7126, longitude: 113.4687)
                            ->showMarker()
                            ->markerColor('#ff0000')
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->clickable(false)
                            ->showMyLocationButton(),
                    ]),

                Section::make('Radius Toleransi')
                    ->schema([
                        TextInput::make('absensi_radius')
                            ->label('Radius Toleransi Absensi (Meter)')
                            ->numeric()
                            ->required()
                            ->helperText('Jarak maksimal (dalam meter) siswa/karyawan diperbolehkan absen dari titik lokasi di atas.'),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $target = $data['target_lokasi'] ?? 'lpk';
        $lat = $data['location']['lat'] ?? null;
        $lng = $data['location']['lng'] ?? null;
        $radius = $data['absensi_radius'] ?? null;

        if ($lat && $lng) {
            if ($target === 'lpk') {
                Setting::updateOrCreate(['key' => 'lpk_latitude'], ['value' => $lat, 'name' => 'LPK Latitude']);
                Setting::updateOrCreate(['key' => 'lpk_longitude'], ['value' => $lng, 'name' => 'LPK Longitude']);
            } else {
                Setting::updateOrCreate(['key' => 'paving_latitude'], ['value' => $lat, 'name' => 'Paving Latitude']);
                Setting::updateOrCreate(['key' => 'paving_longitude'], ['value' => $lng, 'name' => 'Paving Longitude']);
            }
        }
        
        if ($radius) {
            Setting::updateOrCreate(['key' => 'absensi_radius'], ['value' => $radius, 'name' => 'Absensi Radius (M)']);
        }

        Notification::make()
            ->title('Berhasil disimpan')
            ->body('Titik lokasi telah diperbarui.')
            ->success()
            ->send();
    }
}
