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
            $table->string('ten_file')->nullable()->after('file_dinh_kem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bai_taps', function (Blueprint $table) {
            $table->dropColumn('ten_file');
        });
    }
};
