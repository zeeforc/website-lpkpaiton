<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('instansi');
            $table->string('jurusan');
            $table->string('no_hp');
            $table->string('pengajuan');
            $table->string('periode_gelombang');
            $table->string('jumlah_peserta');
            $table->integer('lama_durasi_bulan');
            $table->text('fokus_studi');
            $table->string('email_balasan');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
