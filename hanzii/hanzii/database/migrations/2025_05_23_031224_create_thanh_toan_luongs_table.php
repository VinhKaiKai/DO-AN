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
        Schema::create('thanh_toan_luongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lop_hoc_id')->constrained('lop_hocs')->onDelete('cascade');
            $table->foreignId('giao_vien_id')->nullable()->constrained('giao_viens')->nullOnDelete();
            $table->foreignId('tro_giang_id')->nullable()->constrained('tro_giangs')->nullOnDelete();
            $table->decimal('he_so_luong_giao_vien', 10, 2)->default(0);
            $table->decimal('he_so_luong_tro_giang', 10, 2)->default(0);
            $table->decimal('tong_tien_thu', 20, 2)->default(0);
            $table->decimal('tien_luong_giao_vien', 20, 2)->default(0);
            $table->decimal('tien_luong_tro_giang', 20, 2)->default(0);
            $table->enum('trang_thai_giao_vien', ['chua_thanh_toan', 'da_thanh_toan'])->default('chua_thanh_toan');
            $table->enum('trang_thai_tro_giang', ['chua_thanh_toan', 'da_thanh_toan'])->default('chua_thanh_toan');
            $table->date('ngay_thanh_toan_giao_vien')->nullable();
            $table->date('ngay_thanh_toan_tro_giang')->nullable();
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
        Schema::dropIfExists('thanh_toan_luongs');
    }
};
