<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'siswa', 'guru_pondok', 'karyawan_paving', 'instruktur_lpk') DEFAULT 'siswa'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'siswa', 'guru_pondok', 'karyawan_paving') DEFAULT 'siswa'");
    }
};
