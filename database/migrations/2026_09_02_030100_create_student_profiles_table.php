<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_panggilan')->nullable();
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable();
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
            
            $table->string('pembimbing_industri_nama')->nullable();
            $table->string('pembimbing_industri_hp')->nullable();
            $table->string('guru_pembimbing_nama')->nullable();
            $table->string('guru_pembimbing_hp')->nullable();
            
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('student_profiles');
    }
};
