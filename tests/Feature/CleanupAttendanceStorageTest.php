<?php

namespace Tests\Feature;

use App\Services\AttendanceExportStorage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanupAttendanceStorageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('attendances');
        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->date('date');
            $table->string('photo_path')->nullable();
            $table->text('checkout_photos')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('attendances');
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_deletes_expired_attendance_photos_without_deleting_attendance_records(): void
    {
        Carbon::setTestNow('2026-11-05 01:00:00');
        Storage::fake('public');
        Storage::disk('public')->put('attendances/expired.webp', 'old');
        Storage::disk('public')->put('attendances/shared.webp', 'shared');
        Storage::disk('public')->put('attendances/current.webp', 'current');
        Storage::disk('public')->put('attendances/orphaned.webp', 'old orphan');
        touch(Storage::disk('public')->path('attendances/orphaned.webp'), now()->subDays(40)->getTimestamp());

        DB::table('attendances')->insert([
            [
                'date' => '2026-10-25',
                'photo_path' => 'attendances/expired.webp',
                'checkout_photos' => json_encode(['attendances/shared.webp']),
            ],
            [
                'date' => '2026-11-04',
                'photo_path' => 'attendances/current.webp',
                'checkout_photos' => json_encode(['attendances/shared.webp']),
            ],
        ]);

        $this->artisan('app:cleanup-attendance-photos')->assertExitCode(0);

        $oldAttendance = DB::table('attendances')->whereDate('date', '2026-10-25')->first();
        $currentAttendance = DB::table('attendances')->whereDate('date', '2026-11-04')->first();

        $this->assertDatabaseCount('attendances', 2);
        $this->assertNull($oldAttendance->photo_path);
        $this->assertNull($oldAttendance->checkout_photos);
        $this->assertSame('attendances/current.webp', $currentAttendance->photo_path);
        $this->assertSame(['attendances/shared.webp'], json_decode($currentAttendance->checkout_photos, true));
        Storage::disk('public')->assertMissing('attendances/expired.webp');
        Storage::disk('public')->assertMissing('attendances/orphaned.webp');
        Storage::disk('public')->assertExists('attendances/shared.webp');
        Storage::disk('public')->assertExists('attendances/current.webp');
    }

    public function test_manual_cutoff_removes_september_photos_without_removing_october_photos(): void
    {
        Carbon::setTestNow('2026-11-03 01:00:00');
        Storage::fake('public');
        Storage::disk('public')->put('attendances/september.webp', 'september');
        Storage::disk('public')->put('attendances/october.webp', 'october');

        DB::table('attendances')->insert([
            [
                'date' => '2026-09-25',
                'photo_path' => 'attendances/september.webp',
                'checkout_photos' => null,
            ],
            [
                'date' => '2026-10-25',
                'photo_path' => 'attendances/october.webp',
                'checkout_photos' => null,
            ],
        ]);

        $this->artisan('app:cleanup-attendance-photos', ['--before' => '2026-10-01'])->assertExitCode(0);

        $september = DB::table('attendances')->whereDate('date', '2026-09-25')->first();
        $october = DB::table('attendances')->whereDate('date', '2026-10-25')->first();

        $this->assertNull($september->photo_path);
        $this->assertSame('attendances/october.webp', $october->photo_path);
        Storage::disk('public')->assertMissing('attendances/september.webp');
        Storage::disk('public')->assertExists('attendances/october.webp');
    }

    public function test_it_deletes_only_zip_exports_older_than_six_hours(): void
    {
        Carbon::setTestNow('2026-11-05 08:00:00');
        Storage::fake('local');
        Storage::fake('public');
        $oldZip = 'attendance-exports/4c5cbdd0-8276-4a36-9a25-c9f2bd9f82f4.zip';
        $recentZip = 'attendance-exports/2d020cec-94b9-4a4d-8509-7d16c36cf4be.zip';
        Storage::disk('local')->put($oldZip, 'old');
        Storage::disk('local')->put($recentZip, 'recent');
        touch(Storage::disk('local')->path($oldZip), now()->subHours(7)->getTimestamp());
        touch(Storage::disk('local')->path($recentZip), now()->getTimestamp());
        Storage::disk('local')->put('attendance-exports/b10b24b7-197f-4175-a6f4-076f56d055ba.zip.70ag1h.part', 'partial');
        touch(
            Storage::disk('local')->path('attendance-exports/b10b24b7-197f-4175-a6f4-076f56d055ba.zip.70ag1h.part'),
            now()->subHours(7)->getTimestamp(),
        );
        Storage::disk('public')->put('Dokumentasi Karyawan - Oktober.zip.abc123.part', 'partial');
        Storage::disk('public')->put('Dokumentasi Karyawan - Oktober.zip', 'legacy');
        Storage::disk('public')->put('unrelated.zip.part', 'keep');
        touch(Storage::disk('public')->path('Dokumentasi Karyawan - Oktober.zip.abc123.part'), now()->subHours(7)->getTimestamp());
        touch(Storage::disk('public')->path('Dokumentasi Karyawan - Oktober.zip'), now()->subHours(7)->getTimestamp());

        $this->artisan('app:cleanup-attendance-exports')->assertExitCode(0);

        Storage::disk('local')->assertMissing($oldZip);
        Storage::disk('local')->assertExists($recentZip);
        Storage::disk('local')->assertMissing('attendance-exports/b10b24b7-197f-4175-a6f4-076f56d055ba.zip.70ag1h.part');
        Storage::disk('public')->assertMissing('Dokumentasi Karyawan - Oktober.zip.abc123.part');
        Storage::disk('public')->assertMissing('Dokumentasi Karyawan - Oktober.zip');
        Storage::disk('public')->assertExists('unrelated.zip.part');
    }

    public function test_new_documentation_export_removes_previous_exports_and_legacy_part_files(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $previousZip = 'attendance-exports/f4d90597-5f7d-4184-9f93-60cf2c216671.zip';
        Storage::disk('local')->put($previousZip, 'previous');
        Storage::disk('public')->put('Dokumentasi Karyawan - Oktober.zip.abc123.part', 'partial');
        Storage::disk('public')->put('keep-this-file.part', 'unrelated');

        $deleted = app(AttendanceExportStorage::class)->clearPreviousExports();

        $this->assertSame(2, $deleted);
        Storage::disk('local')->assertMissing($previousZip);
        Storage::disk('public')->assertMissing('Dokumentasi Karyawan - Oktober.zip.abc123.part');
        Storage::disk('public')->assertExists('keep-this-file.part');
    }
}
