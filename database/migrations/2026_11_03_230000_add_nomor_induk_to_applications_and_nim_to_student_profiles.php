<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table): void {
            $table->string('nomor_induk', 50)->nullable()->after('tingkat_pendidikan');
        });

        Schema::table('student_profiles', function (Blueprint $table): void {
            $table->string('nim', 50)->nullable()->after('nisn');
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table): void {
            $table->dropColumn('nim');
        });

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropColumn('nomor_induk');
        });
    }
};
