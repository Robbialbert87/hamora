<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen')->unique();
            $table->string('nama_dokumen');
            $table->year('tahun');
            $table->foreignId('bidang_id')->constrained('bidang')->onDelete('restrict');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('restrict');
            $table->date('tanggal_terbit');
            $table->date('tanggal_berlaku')->nullable();
            $table->integer('versi')->default(1);
            $table->enum('status', ['draft', 'aktif', 'direvisi', 'kadaluarsa'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->string('file_pdf');
            $table->foreignId('parent_document_id')->nullable()->constrained('documents')->onDelete('set null');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
