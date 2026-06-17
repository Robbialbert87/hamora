<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->date('tanggal_pencabutan')->nullable()->after('tanggal_berlaku');
        });

        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('draft', 'aktif', 'direvisi', 'kadaluarsa', 'dicabut') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('draft', 'aktif', 'direvisi', 'kadaluarsa') NOT NULL DEFAULT 'draft'");

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('tanggal_pencabutan');
        });
    }
};
