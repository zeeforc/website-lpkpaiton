<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kurikulums', function (Blueprint $table) {

            if (!Schema::hasColumn('kurikulums', 'slug')) {
                $table->string('slug')->unique()->after('kurikulum_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kurikulums', function (Blueprint $table) {
            $table->dropColumn(['slug']);
        });
    }
};
