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
        Schema::create('thanh_toan_hoc_phis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoc_vien_id')->constrained('hoc_viens')->onDelete('cascade');
            $table->foreignId('lop_hoc_id')->constrained('lop_hocs')->onDelete('cascade');
            $table->decimal('so_tien', 12, 2);
            $table->string('phuong_thuc_thanh_toan')->comment('tien_mat, chuyen_khoan');
            $table->string('trang_thai')->default('chua_thanh_toan')->comment('chua_thanh_toan, da_thanh_toan, da_huy');
            $table->date('ngay_thanh_toan')->nullable();
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
        Schema::dropIfExists('thanh_toan_hoc_phis');
    }
};
