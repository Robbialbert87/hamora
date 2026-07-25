<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('mou')->whereNotNull('masa_berlaku')->update([
            'masa_berlaku' => DB::raw('masa_berlaku * 365'),
        ]);
    }

    public function down(): void
    {
        DB::table('mou')->whereNotNull('masa_berlaku')->update([
            'masa_berlaku' => DB::raw('masa_berlaku / 365'),
        ]);
    }
};
