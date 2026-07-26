<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mou_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mou_id')->constrained('mou')->cascadeOnDelete();
            $table->integer('versi');
            $table->string('nomor');
            $table->string('pihak');
            $table->string('judul');
            $table->date('mulai_perjanjian');
            $table->date('akhir_perjanjian');
            $table->integer('masa_berlaku')->nullable();
            $table->string('file_pdf');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mou_versions');
    }
};
