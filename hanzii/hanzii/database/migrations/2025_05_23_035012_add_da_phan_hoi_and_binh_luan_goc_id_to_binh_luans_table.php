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
        Schema::table('binh_luans', function (Blueprint $table) {
            $table->boolean('da_phan_hoi')->default(false)->after('noi_dung');
            $table->unsignedBigInteger('binh_luan_goc_id')->nullable()->after('da_phan_hoi');
            
            // Thêm khóa ngoại
            $table->foreign('binh_luan_goc_id')
                  ->references('id')
                  ->on('binh_luans')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('binh_luans', function (Blueprint $table) {
            $table->dropForeign(['binh_luan_goc_id']);
            $table->dropColumn('binh_luan_goc_id');
            $table->dropColumn('da_phan_hoi');
        });
    }
};
