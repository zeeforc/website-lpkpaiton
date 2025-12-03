<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kurikulums', function (Blueprint $table) {
            $table->string('image_kurikulum')->nullable()->after('kurikulum_title');
            $table->text('desk_singkat')->nullable()->after('image_kurikulum');
        });
    }

    public function down(): void
    {
        Schema::table('kurikulums', function (Blueprint $table) {
            $table->dropColumn(['image_kurikulum', 'desk_singkat']);
        });
    }
};
