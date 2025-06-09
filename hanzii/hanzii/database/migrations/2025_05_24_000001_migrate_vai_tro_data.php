<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lấy tất cả các liên kết người dùng-vai trò từ bảng trung gian
        $vaiTroNguoiDungs = DB::table('vai_tro_nguoi_dungs')->get();
        
        // Nhóm theo người dùng để xử lý trường hợp 1 người dùng có nhiều vai trò
        $nguoiDungVaiTros = [];
        foreach ($vaiTroNguoiDungs as $item) {
            // Nếu người dùng chưa có trong mảng hoặc vai trò mới có độ ưu tiên cao hơn
            // (giả sử ID vai trò thấp hơn có độ ưu tiên cao hơn, có thể điều chỉnh theo logic của bạn)
            if (!isset($nguoiDungVaiTros[$item->nguoi_dung_id]) || $item->vai_tro_id < $nguoiDungVaiTros[$item->nguoi_dung_id]) {
                $nguoiDungVaiTros[$item->nguoi_dung_id] = $item->vai_tro_id;
            }
        }
        
        // Cập nhật vai_tro_id cho từng người dùng
        foreach ($nguoiDungVaiTros as $nguoiDungId => $vaiTroId) {
            DB::table('nguoi_dungs')
                ->where('id', $nguoiDungId)
                ->update(['vai_tro_id' => $vaiTroId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không thể hoàn tác chính xác, nhưng có thể xóa vai_tro_id
        DB::table('nguoi_dungs')->update(['vai_tro_id' => null]);
    }
}; 