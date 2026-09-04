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
        $lat = Setting::where('key', 'lpk_latitude')->value('value') ?? '-7.7126';
        $lng = Setting::where('key', 'lpk_longitude')->value('value') ?? '113.4687';
        $radius = Setting::where('key', 'absensi_radius')->value('value') ?? '50';

        $this->form->fill([
            'location' => [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ],
            'absensi_radius' => $radius,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Titik Koordinat LPK')
                    ->description('Cari lokasi LPK Paiton Selaras (menggunakan form search di dalam peta) dan geser pin merah ke titik bangunan yang paling tepat.')
                    ->schema([
                        Map::make('location')
                            ->label('Peta Lokasi')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -7.7126, longitude: 113.4687)
                            ->showMarker()
                            ->markerColor('#ff0000')
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->clickable(false)
                            ->showMyLocationButton(),
                            
                        TextInput::make('absensi_radius')
                            ->label('Radius Toleransi Absensi (Meter)')
                            ->numeric()
                            ->required()
                            ->helperText('Jarak maksimal (dalam meter) siswa diperbolehkan absen dari titik lokasi di atas.'),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $lat = $data['location']['lat'] ?? null;
        $lng = $data['location']['lng'] ?? null;
        $radius = $data['absensi_radius'] ?? null;

        if ($lat && $lng) {
            Setting::updateOrCreate(['key' => 'lpk_latitude'], ['value' => $lat, 'name' => 'LPK Latitude']);
            Setting::updateOrCreate(['key' => 'lpk_longitude'], ['value' => $lng, 'name' => 'LPK Longitude']);
        }
        
        if ($radius) {
            Setting::updateOrCreate(['key' => 'absensi_radius'], ['value' => $radius, 'name' => 'Absensi Radius (M)']);
        }

        Notification::make()
            ->title('Berhasil disimpan')
            ->body('Titik lokasi LPK telah diperbarui.')
            ->success()
            ->send();
    }
}
