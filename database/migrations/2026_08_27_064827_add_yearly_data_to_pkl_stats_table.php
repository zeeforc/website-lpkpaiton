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
        Schema::table('pkl_stats', function (Blueprint $table) {
            $table->json('yearly_data')->nullable()->after('jumlah_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pkl_stats', function (Blueprint $table) {
            $table->dropColumn('yearly_data');
        });
    }
};
