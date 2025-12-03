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
        Schema::create('vimis', function (Blueprint $table) {
            $table->id();
            $table->string('visi_title')->default('Visi Kami');
            $table->text('visi_text');
            $table->string('misi_title')->default('Misi Kami');
            $table->text('misi_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vimis');
    }
};
