<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkl_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total_peserta')->default(0)->comment('Total peserta pernah PKL');
            $table->unsignedInteger('peserta_aktif')->default(0)->comment('Peserta sedang PKL saat ini');
            $table->unsignedInteger('jumlah_jurusan')->default(0)->comment('Jumlah jurusan peserta PKL');
            $table->unsignedInteger('jumlah_sekolah')->default(0)->comment('Jumlah asal sekolah / kampus');
            $table->unsignedInteger('jumlah_program')->default(0)->comment('Jumlah program PKL tersedia');
            $table->timestamps();
        });

        // Seed satu baris default agar halaman tidak error
        DB::table('pkl_stats')->insert([
            'total_peserta'   => 0,
            'peserta_aktif'   => 0,
            'jumlah_jurusan'  => 0,
            'jumlah_sekolah'  => 0,
            'jumlah_program'  => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_stats');
    }
};
