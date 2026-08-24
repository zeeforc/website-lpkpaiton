<x-filament-panels::page>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">Unduh Laporan Pendaftar PKL</h2>
                <p class="text-gray-500 text-sm mt-1">Laporan ini berisi daftar seluruh pendaftar yang telah disetujui (Tahap Dokumen maupun Lolos PKL) beserta rangkuman total jumlah peserta di bagian akhir.</p>
            </div>
            
            <x-filament::button wire:click="downloadExcel" icon="heroicon-o-arrow-down-tray" color="success">
                Download Excel
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
