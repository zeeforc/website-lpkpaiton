<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sarana_satus', function (Blueprint $table) {
            $table->id();
            $table->text('sarana_desk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sarana_satus');
    }
};
