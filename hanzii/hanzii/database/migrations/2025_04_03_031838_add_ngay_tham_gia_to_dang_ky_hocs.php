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
        Schema::table('dang_ky_hocs', function (Blueprint $table) {
            $table->timestamp('ngay_tham_gia')->nullable()->after('lop_hoc_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dang_ky_hocs', function (Blueprint $table) {
            $table->dropColumn('ngay_tham_gia');
        });
    }
};
