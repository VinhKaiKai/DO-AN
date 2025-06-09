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
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            $table->string('anh_dai_dien')->nullable()->after('dia_chi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            $table->dropColumn('anh_dai_dien');
        });
    }
};
