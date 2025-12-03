<?php

namespace App\Filament\Widgets;

use App\Models\Berita;
use App\Models\BeritaUtama;
use App\Models\DokumenSyaratPkl;
use App\Models\Galery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BeritaStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Galeri', Galery::count())
                ->description('Jumlah foto saat ini')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('success'),
            Stat::make('Total Berita', BeritaUtama::count())
                ->description('Jumlah berita yang sudah dibuat')
                ->icon('heroicon-o-newspaper')
                ->color('success'),
            Stat::make('Total Dokumen', DokumenSyaratPkl::count())
                ->description('Jumlah dokumen yang sudah di publish')
                ->icon('heroicon-o-clipboard-document')
                ->color('success'),
        ];
    }
}
