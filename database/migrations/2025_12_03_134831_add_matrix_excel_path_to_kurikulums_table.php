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
        Schema::table('kurikulums', function (Blueprint $table) {
            $table->string('matrix_excel_path')
                ->nullable()
                ->after('image_kurikulum'); // sesuaikan posisi kolom kalau mau
        });
    }

    public function down(): void
    {
        Schema::table('kurikulums', function (Blueprint $table) {
            $table->dropColumn('matrix_excel_path');
        });
    }
};
