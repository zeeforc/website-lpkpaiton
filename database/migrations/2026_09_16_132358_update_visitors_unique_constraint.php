<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropUnique(['ip_address', 'visited_date']);
            $table->unique(['session_id', 'visited_date']);
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropUnique(['session_id', 'visited_date']);
            $table->unique(['ip_address', 'visited_date']);
        });
    }
};
