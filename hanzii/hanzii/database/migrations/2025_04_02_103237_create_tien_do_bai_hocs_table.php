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
        Schema::create('tien_do_bai_hocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_hoc_id')->constrained('bai_hocs')->onDelete('cascade');
            $table->foreignId('hoc_vien_id')->constrained('hoc_viens')->onDelete('cascade');
            $table->timestamp('ngay_hoan_thanh')->nullable()->comment('Ngày hoàn thành bài học');
            $table->float('diem')->nullable()->comment('Điểm bài học');
            $table->string('trang_thai')->default('da_bat_dau')->comment('da_bat_dau, dang_hoc, da_hoan_thanh, khong_dat');
            $table->text('ghi_chu')->nullable();
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tien_do_bai_hocs');
    }
};
