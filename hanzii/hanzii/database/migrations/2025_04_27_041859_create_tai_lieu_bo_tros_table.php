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
        Schema::create('tai_lieu_bo_tros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_hoc_id')->constrained('bai_hocs')->onDelete('cascade');
            $table->foreignId('lop_hoc_id')->nullable()->constrained('lop_hocs')->onDelete('cascade');
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->string('duong_dan_file');
            $table->timestamp('tao_luc')->nullable();
            $table->timestamp('cap_nhat_luc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_lieu_bo_tros');
    }
};
