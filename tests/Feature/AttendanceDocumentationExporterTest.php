<?php

namespace Tests\Feature;

use App\Services\AttendanceDocumentationExporter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class AttendanceDocumentationExporterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('attendances');
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('role');
        });
        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('checkout_photos')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_it_streams_a_valid_zip_without_creating_an_archive_file(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::disk('public')->put('attendances/optimized/checkin.webp', 'check-in image bytes');
        Storage::disk('public')->put('attendances/optimized/checkout.webp', 'checkout image bytes');

        $userId = DB::table('users')->insertGetId([
            'name' => 'Test Employee',
            'role' => 'karyawan_paving',
        ]);
        DB::table('attendances')->insert([
            'user_id' => $userId,
            'date' => '2026-10-12',
            'check_in' => '07:00:00',
            'check_out' => '16:00:00',
            'photo_path' => 'attendances/optimized/checkin.webp',
            'checkout_photos' => json_encode(['attendances/optimized/checkout.webp']),
        ]);

        $response = app(AttendanceDocumentationExporter::class)->download(2026, 10);
        ob_start();
        ob_start();
        $response->sendContent();
        ob_end_flush();
        $zipContents = ob_get_clean();

        $temporaryFile = tmpfile();
        fwrite($temporaryFile, $zipContents);
        rewind($temporaryFile);
        $metadata = stream_get_meta_data($temporaryFile);
        $zip = new ZipArchive;

        $this->assertSame(true, $zip->open($metadata['uri']));
        $this->assertSame(2, $zip->numFiles);
        $this->assertSame([], Storage::disk('local')->allFiles('attendance-exports'));
        $this->assertStringContainsString('_Masuk.webp', $zip->getNameIndex(0));
        $this->assertStringContainsString('_Dokumentasi_1.webp', $zip->getNameIndex(1));

        $zip->close();
        fclose($temporaryFile);
    }
}
