<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
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
        \Illuminate\Support\Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_deletes_expired_attendance_photos_without_deleting_attendance_records(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-11-05 01:00:00');
        Storage::fake('public');
        Storage::disk('public')->put('attendances/expired.webp', 'old');
        Storage::disk('public')->put('attendances/shared.webp', 'shared');
        Storage::disk('public')->put('attendances/current.webp', 'current');

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
        Storage::disk('public')->assertExists('attendances/shared.webp');
        Storage::disk('public')->assertExists('attendances/current.webp');
    }

    public function test_it_deletes_only_zip_exports_older_than_six_hours(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-11-05 08:00:00');
        Storage::fake('local');
        Storage::disk('local')->put('attendance-exports/old.zip', 'old');
        Storage::disk('local')->put('attendance-exports/recent.zip', 'recent');
        touch(Storage::disk('local')->path('attendance-exports/old.zip'), now()->subHours(7)->getTimestamp());
        touch(Storage::disk('local')->path('attendance-exports/recent.zip'), now()->getTimestamp());

        $this->artisan('app:cleanup-attendance-exports')->assertExitCode(0);

        Storage::disk('local')->assertMissing('attendance-exports/old.zip');
        Storage::disk('local')->assertExists('attendance-exports/recent.zip');
    }
}
