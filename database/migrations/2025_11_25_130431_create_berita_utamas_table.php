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
        Schema::create('berita_utamas', function (Blueprint $table) {
            $table->id();
            $table->string('berita_utama_title');
            $table->string('berita_utama_image');
            $table->text('berita_utama_desk');
            $table->string('tgl_berita');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_utamas');
    }
};
