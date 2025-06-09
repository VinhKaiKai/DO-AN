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
        Schema::create('bai_hocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khoa_hoc_id')->constrained('khoa_hocs')->onDelete('cascade');
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->integer('so_thu_tu')->default(0);
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
            $table->longText('noi_dung')->nullable();
            $table->string('loai')->default('van_ban');
            $table->string('url_video')->nullable();
            $table->integer('thoi_luong')->default(45);
            $table->string('trang_thai')->default('chua_xuat_ban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_hocs');
    }
};
