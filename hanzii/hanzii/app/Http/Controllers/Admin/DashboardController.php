<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiTap;
use App\Models\BaiTapDaNop;
use App\Models\DangKyHoc;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NguoiDung;
use App\Models\ThanhToan;
use App\Models\ThongBao;
use App\Models\TroGiang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //hiển thị cái đát bo
    public function index(Request $request)
    {
        $data = [
            // Thông tin thống kê tổng quan người dùng
            ...$this->getNguoiDungStats(),
            
            // Thông tin thống kê khóa học và lớp học
            ...$this->getKhoaHocLopHocStats(),
            // Danh sách mới nhất
            ...$this->getLatestRecords(),
          
        ];
        return view('admin.dashboard', $data);
    }
    
    // thống kê người dùng. 
    private function getNguoiDungStats()
    {
        // Tổng số người dùng theo vai trò
        $tongNguoiDung = NguoiDung::count();
        $tongHocVien = HocVien::count();
        $tongGiaoVien = GiaoVien::count();
        $tongTroGiang = TroGiang::count();
        $tongNhanVien = $tongGiaoVien + $tongTroGiang;
              
        return compact(
            'tongNguoiDung',
            'tongHocVien',
            'tongGiaoVien',
            'tongTroGiang',
            'tongNhanVien',
        );
    }
    
    // lấy khóa và lớp 
    private function getKhoaHocLopHocStats()
    {
        // Thống kê khóa học
        $tongKhoaHoc = KhoaHoc::count();
        $khoaHocHoatDong = KhoaHoc::where('trang_thai', 'hoat_dong')->count();
        
        // Thống kê lớp học
        $tongLopHoc = LopHoc::count();
        $lopHocDangHoatDong = LopHoc::where('trang_thai', 'hoat_dong')->count();
        $lopHocSapKhaiGiang = LopHoc::where('trang_thai', 'sap_khai_giang')->count();
        $lopHocDaKetThuc = LopHoc::where('trang_thai', 'da_ket_thuc')->count();
        
        // Lớp học sắp khai giảng
        $danhSachLopHocSapKhaiGiang = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung'])
            ->where('trang_thai', 'sap_khai_giang')
            ->where('ngay_bat_dau', '>', now())
            ->orderBy('ngay_bat_dau', 'asc')
            ->take(7)
            ->get();
            
        return compact(
            'tongKhoaHoc',
            'khoaHocHoatDong',
            'tongLopHoc',
            'lopHocDangHoatDong',
            'lopHocSapKhaiGiang',
            'lopHocDaKetThuc',
            'danhSachLopHocSapKhaiGiang',
            
           
        );
    }
    
  
    /**
     * Lấy các danh sách mới nhất
     *
     * @return array
     */
    private function getLatestRecords()
    {
        // Đăng ký học mới
        $dangKyHocMoi = DangKyHoc::with(['hocVien.nguoiDung', 'lopHoc.khoaHoc'])
            ->where('trang_thai', 'cho_xac_nhan')
            ->orderBy('tao_luc', 'desc')
            ->take(5)
            ->get();
        
        // Thông báo mới nhất
        $thongBaoMoiNhat = ThongBao::with('nguoiDung')
            ->orderBy('tao_luc', 'desc')
            ->take(5)
            ->get();
        
        return compact('dangKyHocMoi', 'thongBaoMoiNhat');
    }

 
} 