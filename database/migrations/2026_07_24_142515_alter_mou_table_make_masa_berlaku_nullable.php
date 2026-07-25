<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->integer('masa_berlaku')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->integer('masa_berlaku')->nullable(false)->change();
        });
    }
};
