<?php

namespace App\Filament\Amsadmin\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPendaftar = Application::count();
        $belumDireview = Application::whereIn('status', ['pending', 'document_review'])->count();
        $lolos = Application::where('status', 'accepted')->count();

        return [
            Stat::make('Total Pendaftar', $totalPendaftar)
                ->description('Keseluruhan pendaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Belum Direview', $belumDireview)
                ->description('Menunggu aksi Admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Lolos PKL', $lolos)
                ->description('Pendaftar yang diterima')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
