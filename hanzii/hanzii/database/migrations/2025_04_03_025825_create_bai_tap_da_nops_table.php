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
        Schema::create('bai_tap_da_nops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bai_tap_id');
            $table->unsignedBigInteger('hoc_vien_id');
            $table->text('noi_dung')->nullable();
            $table->string('file_path')->nullable();
            $table->string('ten_file')->nullable();
            $table->decimal('diem', 5, 2)->nullable();
            $table->enum('trang_thai', ['da_nop', 'dang_cham', 'da_cham', 'yeu_cau_nop_lai'])->default('da_nop');
            $table->datetime('ngay_nop');
            $table->unsignedBigInteger('nguoi_cham_id')->nullable();
            $table->text('phan_hoi')->nullable();
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
            $table->foreign('bai_tap_id')->references('id')->on('bai_taps')->onDelete('cascade');
            $table->foreign('hoc_vien_id')->references('id')->on('hoc_viens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_tap_da_nops');
    }
};
