<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->integer('versi')->default(1)->after('status');
            $table->foreignId('parent_mou_id')->nullable()->after('versi')->constrained('mou')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mou', function (Blueprint $table) {
            $table->dropForeign(['parent_mou_id']);
            $table->dropColumn(['versi', 'parent_mou_id']);
        });
    }
};
