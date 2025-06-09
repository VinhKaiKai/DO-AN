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
        Schema::create('vai_tros', function (Blueprint $table) {
            $table->id();
            $table->string('ten')->comment('quan_tri, giao_vien, tro_giang, hoc_vien');
            $table->text('mo_ta')->nullable();
            $table->float('he_so_luong')->default(0)->comment('Tỷ lệ lương (%), chỉ áp dụng cho giáo viên và trợ giảng');
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vai_tros');
    }
};
