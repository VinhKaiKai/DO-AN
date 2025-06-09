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
        Schema::create('tro_giangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dungs')->onDelete('cascade');
            $table->string('bang_cap')->nullable();
            $table->string('trinh_do')->nullable()->comment('Trình độ học vấn');
            $table->integer('so_nam_kinh_nghiem')->default(0);
            $table->string('chuyen_mon')->nullable()->comment('Chuyên môn giảng dạy, ví dụ: hsk1,hsk2,hsk3');
            $table->timestamp('tao_luc')->nullable();
            $table->timestamp('cap_nhat_luc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tro_giangs');
    }
}; 