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
        Schema::create('bai_taps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_hoc_id')->constrained('bai_hocs')->onDelete('cascade');
            $table->string('tieu_de');
            $table->string('loai')->comment('tu_luan, file');
            $table->text('noi_dung')->nullable();
            $table->float('diem_toi_da')->default(10);
            $table->timestamp('han_nop')->nullable();
            $table->enum('trang_thai', ['chua_xuat_ban', 'da_xuat_ban'])->default('chua_xuat_ban');
            $table->timestamp('tao_luc')->useCurrent();
            $table->timestamp('cap_nhat_luc')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_taps');
    }
};
