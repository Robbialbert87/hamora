<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_bukti_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_bukti_id')->constrained('rekap_buktis')->cascadeOnDelete();
            $table->string('label');
            $table->string('tipe', 20);
            $table->boolean('required')->default(false);
            $table->json('options')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_bukti_fields');
    }
};
