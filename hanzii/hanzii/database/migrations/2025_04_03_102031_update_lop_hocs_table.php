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
        // Xóa khóa ngoại cũ
        Schema::table('lop_hocs', function (Blueprint $table) {
            $table->dropForeign(['tro_giang_id']);
        });

        // Thêm khóa ngoại mới
        Schema::table('lop_hocs', function (Blueprint $table) {
            $table->foreign('tro_giang_id')->references('id')->on('tro_giangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lop_hocs', function (Blueprint $table) {
            $table->dropForeign(['tro_giang_id']);
            $table->foreign('tro_giang_id')->references('id')->on('giao_viensd');
        });
    }
}; 