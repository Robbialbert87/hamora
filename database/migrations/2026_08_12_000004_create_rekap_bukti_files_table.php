<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_bukti_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('rekap_bukti_records')->cascadeOnDelete();
            $table->foreignId('rekap_bukti_id')->constrained('rekap_buktis')->cascadeOnDelete();
            $table->foreignId('field_id')->nullable()->constrained('rekap_bukti_fields')->nullOnDelete();
            $table->string('nama_asli');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_bukti_files');
    }
};
