<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('reference_code', 10)->nullable()->unique()->after('id');
        });

        // Backfill existing rows
        DB::table('applications')->whereNull('reference_code')->orderBy('id')->each(function ($app) {
            DB::table('applications')->where('id', $app->id)->update([
                'reference_code' => strtoupper(Str::random(8)),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('reference_code');
        });
    }
};
