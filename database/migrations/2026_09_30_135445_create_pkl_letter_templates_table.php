<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkl_letter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Template Surat Balasan"
            $table->string('type')->unique(); // 'surat_balasan' or 'surat_perjanjian'
            $table->string('file_path');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_letter_templates');
    }
};
