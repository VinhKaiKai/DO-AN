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
        Schema::table('tai_lieu_bo_tros', function (Blueprint $table) {
            $table->string('ten')->nullable()->after('tieu_de');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tai_lieu_bo_tros', function (Blueprint $table) {
            $table->dropColumn('ten');
        });
    }
};
