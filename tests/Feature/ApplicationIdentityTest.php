<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ApplicationIdentityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('application_documents');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->nullable();
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nama_lengkap');
            $table->string('instansi');
            $table->string('tingkat_pendidikan')->nullable();
            $table->string('nomor_induk')->nullable();
            $table->string('jurusan');
            $table->string('no_hp');
            $table->string('pengajuan');
            $table->string('periode_gelombang');
            $table->string('jumlah_peserta');
            $table->integer('lama_durasi_bulan');
            $table->text('fokus_studi');
            $table->string('email_balasan');
            $table->string('status')->default('pending');
            $table->date('end_date')->nullable();
            $table->boolean('is_jalur_khusus')->default(false);
            $table->string('reference_code')->nullable();
            $table->timestamps();
        });

        Schema::create('student_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nama_panggilan')->nullable();
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable();
            $table->string('nim')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('npsn')->nullable();
            $table->string('kelas')->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->string('nama_wali_kelas')->nullable();
            $table->string('no_hp_wali_kelas')->nullable();
            $table->string('nama_kontak_darurat')->nullable();
            $table->string('hubungan_kontak_darurat')->nullable();
            $table->string('no_hp_kontak_darurat')->nullable();
            $table->text('alamat_kontak_darurat')->nullable();
            $table->string('guru_pembimbing_nama')->nullable();
            $table->string('guru_pembimbing_hp')->nullable();
            $table->string('face_descriptor')->nullable();
            $table->string('pas_foto')->nullable();
            $table->timestamps();
        });

        Schema::create('application_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('application_documents');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_identity_migration_adds_and_removes_the_new_columns(): void
    {
        Schema::table('applications', function (Blueprint $table): void {
            $table->dropColumn('nomor_induk');
        });
        Schema::table('student_profiles', function (Blueprint $table): void {
            $table->dropColumn('nim');
        });

        $migration = require database_path('migrations/2026_11_03_230000_add_nomor_induk_to_applications_and_nim_to_student_profiles.php');
        $migration->up();

        $this->assertTrue(Schema::hasColumn('applications', 'nomor_induk'));
        $this->assertTrue(Schema::hasColumn('student_profiles', 'nim'));

        $migration->down();

        $this->assertFalse(Schema::hasColumn('applications', 'nomor_induk'));
        $this->assertFalse(Schema::hasColumn('student_profiles', 'nim'));
    }

    public function test_registration_saves_student_nisn_and_college_nim_as_strings(): void
    {
        Storage::fake('public');

        foreach ([
            ['Siswa SMK/SMA', '0001234567', 'nisn'],
            ['Mahasiswa', '0000987654', 'nim'],
        ] as [$education, $identityNumber, $profileField]) {
            $this->post(route('application.store'), [
                'nama_lengkap' => 'Pendaftar '.$education,
                'instansi' => 'Instansi',
                'tingkat_pendidikan' => $education,
                'nomor_induk' => $identityNumber,
                'jurusan' => 'Teknik',
                'no_hp' => '08123456789',
                'pengajuan' => 'Praktek Kerja Lapangan',
                'periode_gelombang' => 'Januari',
                'lama_durasi_bulan' => 3,
                'fokus_studi' => 'Pembelajaran',
                'email_balasan' => strtolower(str_replace([' ', '/'], '', $education)).'@example.com',
                'documents' => [UploadedFile::fake()->create('surat.pdf', 2, 'application/pdf')],
            ])->assertSessionHasNoErrors();

            $application = Application::latest('id')->first();

            $this->assertSame($identityNumber, $application->nomor_induk);
            $this->assertSame($identityNumber, $application->user->studentProfile->{$profileField});
        }
    }

    public function test_duplicate_name_scope_ignores_case_and_outer_whitespace(): void
    {
        foreach ([' Nama Sama ', 'nama sama', 'Nama Lain'] as $index => $name) {
            $user = User::create([
                'name' => $name,
                'email' => "user{$index}@example.com",
                'password' => 'password',
            ]);

            Application::create([
                'user_id' => $user->id,
                'nama_lengkap' => $name,
                'instansi' => 'Instansi',
                'tingkat_pendidikan' => 'Mahasiswa',
                'nomor_induk' => '000123',
                'jurusan' => 'Teknik',
                'no_hp' => '08123456789',
                'pengajuan' => 'PKL',
                'periode_gelombang' => 'Januari',
                'jumlah_peserta' => '1 ORANG',
                'lama_durasi_bulan' => 3,
                'fokus_studi' => 'Pembelajaran',
                'email_balasan' => "user{$index}@example.com",
                'status' => 'pending',
            ]);
        }

        $this->assertSame(
            [' Nama Sama ', 'nama sama'],
            Application::query()->withDuplicateNames()->orderBy('id')->pluck('nama_lengkap')->all(),
        );
    }

    public function test_student_biodata_shows_nim_and_does_not_accept_removed_homeroom_fields(): void
    {
        $user = User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => 'password',
        ]);
        $profile = StudentProfile::create([
            'user_id' => $user->id,
            'nis' => 'old-nis',
            'nisn' => 'old-nisn',
            'nama_wali_kelas' => 'Data lama',
        ]);
        Application::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Mahasiswa',
            'instansi' => 'Perguruan Tinggi',
            'tingkat_pendidikan' => 'Mahasiswa',
            'nomor_induk' => '0000123',
            'jurusan' => 'Teknik',
            'no_hp' => '08123456789',
            'pengajuan' => 'PKL',
            'periode_gelombang' => 'Januari',
            'jumlah_peserta' => '1 ORANG',
            'lama_durasi_bulan' => 3,
            'fokus_studi' => 'Pembelajaran',
            'email_balasan' => $user->email,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('portal.biodata'))
            ->assertOk()
            ->assertSee('NIM')
            ->assertDontSee('name="nis"')
            ->assertDontSee('name="nisn"')
            ->assertDontSee('name="nama_wali_kelas"')
            ->assertDontSee('name="no_hp_wali_kelas"');

        $this->actingAs($user)
            ->post(route('portal.biodata.update'), [
                'nim' => '0000456',
                'nama_wali_kelas' => 'Tidak boleh disimpan',
                'no_hp_wali_kelas' => '08111111111',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('0000456', $profile->fresh()->nim);
        $this->assertSame('old-nis', $profile->fresh()->nis);
        $this->assertSame('Data lama', $profile->fresh()->nama_wali_kelas);

        $this->actingAs($user)
            ->post(route('portal.biodata.update'), ['nim' => 'NIM-456'])
            ->assertSessionHasErrors('nim');
    }

    public function test_student_biodata_shows_nis_and_nisn_without_homeroom_fields(): void
    {
        $user = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@example.com',
            'password' => 'password',
        ]);
        StudentProfile::create(['user_id' => $user->id]);
        Application::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Siswa',
            'instansi' => 'SMK',
            'tingkat_pendidikan' => 'Siswa SMK/SMA',
            'nomor_induk' => '0000123456',
            'jurusan' => 'Teknik',
            'no_hp' => '08123456789',
            'pengajuan' => 'PKL',
            'periode_gelombang' => 'Januari',
            'jumlah_peserta' => '1 ORANG',
            'lama_durasi_bulan' => 3,
            'fokus_studi' => 'Pembelajaran',
            'email_balasan' => $user->email,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('portal.biodata'))
            ->assertOk()
            ->assertSee('NIS')
            ->assertSee('NISN')
            ->assertDontSee('name="nim"')
            ->assertDontSee('name="nama_wali_kelas"')
            ->assertDontSee('name="no_hp_wali_kelas"');
    }

    public function test_external_missing_document_upload_moves_approved_application_to_document_review(): void
    {
        Storage::fake('public');
        Mail::fake();

        $application = $this->buildTestApplication('permohonan_diterima');
        ApplicationDocument::create([
            'application_id' => $application->id,
            'file_path' => 'applications/documents/initial.pdf',
            'original_name' => 'Surat Pengantar - awal.pdf',
            'status' => 'Valid',
        ]);
        $url = URL::temporarySignedRoute(
            'application.dokumen.upload-missing',
            now()->addMinutes(10),
            ['application' => $application->id],
        );

        $this->post($url, [
            'document_type' => 'KTP/Kartu Pelajar',
            'dokumen_baru' => UploadedFile::fake()->create('ktp.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $this->assertSame('document_review', $application->fresh()->status);
        $this->assertDatabaseHas('application_documents', [
            'application_id' => $application->id,
            'original_name' => 'KTP/Kartu Pelajar - ktp.pdf',
            'status' => 'Menunggu Review',
        ]);
    }

    public function test_external_document_reupload_returns_revision_application_to_document_review(): void
    {
        Storage::fake('public');
        Mail::fake();

        $application = $this->buildTestApplication('revisi_dokumen');
        $document = ApplicationDocument::create([
            'application_id' => $application->id,
            'file_path' => 'applications/documents/old.pdf',
            'original_name' => 'KTP/Kartu Pelajar - lama.pdf',
            'status' => 'Revisi',
            'keterangan' => 'Unggah ulang dokumen.',
        ]);
        Storage::disk('public')->put($document->file_path, 'old document');
        $url = URL::temporarySignedRoute(
            'application.dokumen.reupload',
            now()->addMinutes(10),
            ['application' => $application->id, 'document' => $document->id],
        );

        $this->post($url, [
            'dokumen_baru' => UploadedFile::fake()->create('ktp-baru.pdf', 20, 'application/pdf'),
        ])->assertRedirect();

        $this->assertSame('document_review', $application->fresh()->status);
        $this->assertSame('Menunggu Review', $document->fresh()->status);
        $this->assertNull($document->fresh()->keterangan);
        Storage::disk('public')->assertMissing('applications/documents/old.pdf');
    }

    private function buildTestApplication(string $status): Application
    {
        $user = User::create([
            'name' => 'Pendaftar',
            'email' => 'pendaftar-'.uniqid().'@example.com',
            'password' => 'password',
        ]);

        return Application::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Pendaftar',
            'instansi' => 'Instansi',
            'tingkat_pendidikan' => 'Mahasiswa',
            'nomor_induk' => '000123',
            'jurusan' => 'Teknik',
            'no_hp' => '08123456789',
            'pengajuan' => 'PKL',
            'periode_gelombang' => 'Januari',
            'jumlah_peserta' => '1 ORANG',
            'lama_durasi_bulan' => 3,
            'fokus_studi' => 'Pembelajaran',
            'email_balasan' => $user->email,
            'status' => $status,
        ]);
    }
}
