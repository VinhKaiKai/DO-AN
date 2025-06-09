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
        Schema::create('thong_baos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('users')->onDelete('cascade');
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->string('loai')->nullable()->comment('Loại thông báo: hệ thống, thanh_toán, lớp_học, v.v.');
            $table->boolean('da_doc')->default(false);
            $table->string('url')->nullable()->comment('Đường dẫn liên kết khi click vào thông báo');
            $table->timestamp('ngay_doc')->nullable()->comment('Thời điểm người dùng đọc thông báo');
            $table->timestamp('tao_luc')->nullable();
            $table->timestamp('cap_nhat_luc')->nullable();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_baos');
    }
};
