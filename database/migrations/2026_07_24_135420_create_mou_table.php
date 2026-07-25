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
        Schema::create('mou', function (Blueprint $table) {
            $table->id();
            $table->string('pihak');
            $table->string('judul');
            $table->string('nomor')->unique();
            $table->date('mulai_perjanjian');
            $table->integer('masa_berlaku');
            $table->date('akhir_perjanjian');
            $table->enum('status', ['aktif', 'kadaluarsa', 'dicabut'])->default('aktif');
            $table->string('file_pdf');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mou');
    }
};
