<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete()->after('judul');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete()->after('bidang_id');
        });
    }

    public function down(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropForeign(['kategori_id']);
            $table->dropColumn(['bidang_id', 'kategori_id']);
        });
    }
};