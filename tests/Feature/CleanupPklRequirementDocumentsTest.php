<?php

namespace Tests\Feature;

use App\Models\DokumenSyaratPkl;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanupPklRequirementDocumentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('dokumen_syarat_pkls');
        Schema::create('dokumen_syarat_pkls', function (Blueprint $table): void {
            $table->id();
            $table->string('dokumen_syarat_pkl_title');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('dokumen_syarat_pkls');

        parent::tearDown();
    }

    public function test_it_removes_older_documents_and_their_files_when_a_new_document_is_uploaded(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('dokumen_syarat_pkl/old.pdf', 'old');
        Storage::disk('public')->put('dokumen_syarat_pkl/latest.pdf', 'latest');

        DB::table('dokumen_syarat_pkls')->insert([
            [
                'dokumen_syarat_pkl_title' => 'Dokumen lama',
                'file_path' => 'dokumen_syarat_pkl/old.pdf',
                'created_at' => '2026-02-01 00:00:00',
                'updated_at' => '2026-02-01 00:00:00',
            ],
        ]);

        $latest = DokumenSyaratPkl::create([
            'dokumen_syarat_pkl_title' => 'Dokumen terbaru',
            'file_path' => 'dokumen_syarat_pkl/latest.pdf',
        ]);

        $this->assertDatabaseCount('dokumen_syarat_pkls', 1);
        $this->assertDatabaseHas('dokumen_syarat_pkls', [
            'id' => $latest->id,
            'file_path' => 'dokumen_syarat_pkl/latest.pdf',
        ]);
        Storage::disk('public')->assertMissing('dokumen_syarat_pkl/old.pdf');
        Storage::disk('public')->assertExists('dokumen_syarat_pkl/latest.pdf');
    }

    public function test_cleanup_command_keeps_the_most_recent_existing_document(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('dokumen_syarat_pkl/older.pdf', 'older');
        Storage::disk('public')->put('dokumen_syarat_pkl/newer.pdf', 'newer');

        DB::table('dokumen_syarat_pkls')->insert([
            [
                'dokumen_syarat_pkl_title' => 'Dokumen lama',
                'file_path' => 'dokumen_syarat_pkl/older.pdf',
                'created_at' => '2026-01-01 00:00:00',
                'updated_at' => '2026-01-01 00:00:00',
            ],
            [
                'dokumen_syarat_pkl_title' => 'Dokumen terbaru',
                'file_path' => 'dokumen_syarat_pkl/newer.pdf',
                'created_at' => '2026-02-01 00:00:00',
                'updated_at' => '2026-02-01 00:00:00',
            ],
        ]);

        $this->artisan('app:cleanup-pkl-requirement-documents')->assertExitCode(0);

        $this->assertDatabaseCount('dokumen_syarat_pkls', 1);
        $this->assertDatabaseHas('dokumen_syarat_pkls', ['file_path' => 'dokumen_syarat_pkl/newer.pdf']);
        Storage::disk('public')->assertMissing('dokumen_syarat_pkl/older.pdf');
        Storage::disk('public')->assertExists('dokumen_syarat_pkl/newer.pdf');
    }
}
