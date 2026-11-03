<?php

namespace Tests\Feature;

use App\Jobs\OptimizeAttendancePhotos;
use App\Services\AttendancePhotoLock;
use App\Services\AttendancePhotoMigration;
use App\Services\ImageOptimizer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendancePhotoMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('attendances');
        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->date('date');
            $table->string('photo_path')->nullable();
            $table->json('checkout_photos')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('attendances');

        parent::tearDown();
    }

    public function test_it_migrates_shared_legacy_photo_references_once_and_removes_the_jpeg(): void
    {
        Storage::fake('public');
        $image = imagecreatetruecolor(20, 20);
        ob_start();
        imagejpeg($image);
        $jpeg = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put('attendances/shared.jpg', $jpeg);
        DB::table('attendances')->insert([
            [
                'date' => '2026-10-01',
                'photo_path' => 'attendances/shared.jpg',
                'checkout_photos' => json_encode(['attendances/shared.jpg']),
            ],
            [
                'date' => '2026-10-02',
                'photo_path' => 'attendances/shared.jpg',
                'checkout_photos' => json_encode(['attendances/shared.jpg']),
            ],
        ]);

        $result = app(AttendancePhotoMigration::class)->migrateBatch(
            app(ImageOptimizer::class),
            app(AttendancePhotoLock::class),
            1,
        );

        $records = DB::table('attendances')->orderBy('id')->get();
        $newPath = $records[0]->photo_path;

        $this->assertSame(1, $result['converted']);
        $this->assertSame(0, $result['failed']);
        $this->assertStringEndsWith('.webp', $newPath);
        $this->assertSame($newPath, $records[1]->photo_path);
        $this->assertSame([$newPath], json_decode($records[0]->checkout_photos, true));
        $this->assertSame([$newPath], json_decode($records[1]->checkout_photos, true));
        Storage::disk('public')->assertMissing('attendances/shared.jpg');
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_it_optimizes_new_checkout_photos_for_their_attendance_only(): void
    {
        Storage::fake('public');
        $image = imagecreatetruecolor(20, 20);
        ob_start();
        imagejpeg($image);
        $jpeg = ob_get_clean();
        imagedestroy($image);

        $path = 'attendances/checkout/new-checkout.jpg';
        Storage::disk('public')->put($path, $jpeg);
        $attendanceId = DB::table('attendances')->insertGetId([
            'date' => '2026-10-01',
            'checkout_photos' => json_encode([$path]),
        ]);
        DB::table('attendances')->insert([
            'date' => '2026-10-02',
            'checkout_photos' => json_encode(['attendances/checkout/other.jpg']),
        ]);

        (new OptimizeAttendancePhotos($attendanceId, [$path]))->handle(
            app(AttendancePhotoMigration::class),
            app(ImageOptimizer::class),
            app(AttendancePhotoLock::class),
        );

        $attendance = DB::table('attendances')->where('id', $attendanceId)->first();
        $otherAttendance = DB::table('attendances')->where('id', '!=', $attendanceId)->first();
        $newPath = json_decode($attendance->checkout_photos, true)[0];

        $this->assertStringEndsWith('.webp', $newPath);
        $this->assertSame(['attendances/checkout/other.jpg'], json_decode($otherAttendance->checkout_photos, true));
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($newPath);
    }
}
