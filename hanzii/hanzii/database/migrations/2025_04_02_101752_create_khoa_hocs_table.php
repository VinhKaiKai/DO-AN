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
        Schema::create('khoa_hocs', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->decimal('hoc_phi', 12, 2)->default(0);
            $table->integer('tong_so_bai')->default(0);
            $table->string('thoi_gian_hoan_thanh')->nullable();
            $table->string('trang_thai')->default('hoat_dong');
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoa_hocs');
    }
};
