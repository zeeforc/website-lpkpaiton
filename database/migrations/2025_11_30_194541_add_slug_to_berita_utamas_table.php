<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // cek dulu, kalau sudah ada kolom 'slug' jangan tambah lagi
        if (! Schema::hasColumn('berita_utamas', 'slug')) {
            Schema::table('berita_utamas', function (Blueprint $table) {
                $table->string('slug')->unique()->after('berita_utama_title');
            });
        }
    }

    public function down(): void
    {
        // kalau nanti di rollback, aman saja
        if (Schema::hasColumn('berita_utamas', 'slug')) {
            Schema::table('berita_utamas', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
