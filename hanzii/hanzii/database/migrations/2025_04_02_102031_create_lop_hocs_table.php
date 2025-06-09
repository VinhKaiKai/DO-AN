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
        Schema::create('lop_hocs', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma_lop')->unique();
            $table->foreignId('khoa_hoc_id')->constrained('khoa_hocs')->onDelete('cascade');
            $table->foreignId('giao_vien_id')->constrained('giao_viens');
            $table->foreignId('tro_giang_id')->constrained('tro_giangs');
            $table->string('hinh_thuc_hoc')->comment('online, offline');
            $table->text('lich_hoc')->comment('Lịch học hàng tuần');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->integer('so_luong_toi_da')->default(30);
            $table->string('trang_thai')->default('sap_khai_giang')->comment('dang_hoc, da_hoan_thanh, sap_khai_giang');
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lop_hocs');
    }
};
