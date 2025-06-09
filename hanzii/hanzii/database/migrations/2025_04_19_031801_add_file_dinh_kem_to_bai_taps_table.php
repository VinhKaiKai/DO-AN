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
        Schema::table('bai_taps', function (Blueprint $table) {
            $table->string('file_dinh_kem')->nullable()->after('han_nop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bai_taps', function (Blueprint $table) {
            $table->dropColumn('file_dinh_kem');
        });
    }
};
