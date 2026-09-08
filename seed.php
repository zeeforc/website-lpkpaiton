<?php
$user = \App\Models\User::where('role', 'karyawan_paving')->first();
if (!$user) {
    $user = \App\Models\User::create(['name' => 'Septian Sandy Pranata', 'email' => 'septian@test.com', 'password' => bcrypt('password'), 'role' => 'karyawan_paving']);
}

$bulanSekarang = date('m');
$tahunSekarang = date('Y');

foreach([1, 2, 3, 4, 5, 6, 9, 10, 11, 12, 13] as $day) {
    $tanggal = sprintf('%04d-%02d-%02d', $tahunSekarang, $bulanSekarang, $day);
    if (\Carbon\Carbon::parse($tanggal)->isWeekend()) continue;

    \App\Models\Attendance::firstOrCreate(
        ['user_id' => $user->id, 'date' => $tanggal],
        [
            'status' => 'Hadir',
            'check_in' => '07:00:00',
            'check_out' => '16:00:00',
            'work_description' => 'Pindah cetak paving, trial cetak kanstin'
        ]
    );
}
echo "Data berhasil dibuat!\n";
