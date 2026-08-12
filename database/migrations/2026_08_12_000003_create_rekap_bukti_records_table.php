<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_bukti_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_bukti_id')->constrained('rekap_buktis')->cascadeOnDelete();
            $table->json('data')->nullable();
            $table->string('pengisi_nama')->nullable();
            $table->string('pengisi_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_bukti_records');
    }
};
