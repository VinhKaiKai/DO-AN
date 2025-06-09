<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\LuongGiaoVien;
use App\Models\NguoiDung;
use App\Models\ThanhToan;
use App\Models\ThongKeTaiChinh;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Luong;
use App\Models\GiaoVien;
use App\Models\TroGiang;

class ThongKeController extends Controller
{
    /**
     * Hiển thị thống kê tổng quan
     */
    public function tongQuan()
    {
        // Số lượng học viên
        $tongHocVien = HocVien::count();
        $hocVienMoi = HocVien::whereMonth('tao_luc', Carbon::now()->month)
            ->whereYear('tao_luc', Carbon::now()->year)
            ->count();
        
        // Số lượng giáo viên và trợ giảng
        $tongGiaoVien = GiaoVien::count();
        $tongTroGiang = TroGiang::count();
        
        // Số lượng lớp học và khóa học
        $tongLopHoc = LopHoc::count();
        $lopHocDangDienRa = LopHoc::where('trang_thai', 'dang_dien_ra')->count();
        $tongKhoaHoc = KhoaHoc::count();
        
     
        // Lấy dữ liệu doanh thu theo tháng trong năm hiện tại
        $dataDoanhThu = $this->getDoanhThuTheoThang(Carbon::now()->year);
        
        
        // Lấy dữ liệu lợi nhuận theo tháng trong năm hiện tại
        $dataLoiNhuan = [];
        
        return view('admin.thong-ke.tong-quan', compact(
            'tongHocVien', 
            'hocVienMoi', 
            'tongGiaoVien', 
            'tongTroGiang', 
            'tongLopHoc', 
            'lopHocDangDienRa', 
            'tongKhoaHoc', 
            'doanhThuThang',
            'dataDoanhThu',
            'dataLoiNhuan'
        ));
    }
    
    /**
     * Hiển thị thống kê học viên
     */
    public function hocVien(Request $request)
    {
        $nam = $request->input('nam', Carbon::now()->year);
        
        // Lấy dữ liệu học viên theo tháng
        $hocViens = HocVien::select(
                DB::raw('MONTH(tao_luc) as thang'),
                DB::raw('COUNT(*) as so_luong')
            )
            ->whereYear('tao_luc', $nam)
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();
        
        // Chuẩn bị dữ liệu cho biểu đồ
        $labels = [];
        $soLuong = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = 'Tháng ' . $i;
            
            // Tìm dữ liệu cho tháng hiện tại
            $data = $hocViens->firstWhere('thang', $i);
            
            $soLuong[] = $data ? $data->so_luong : 0;
        }
        
        // Tính tổng học viên
        $tongHocVien = HocVien::count();
        
        // Thống kê học viên đang học
        $hocVienDangHoc = DangKyHoc::where('trang_thai', 'da_xac_nhan')->count();
        
        // Thống kê học viên mới trong tháng hiện tại
        $hocVienMoiThangNay = HocVien::whereMonth('tao_luc', Carbon::now()->month)
            ->whereYear('tao_luc', Carbon::now()->year)
            ->count();
        
        // Thống kê đăng ký chờ xử lý
        $dangKyChoXuLy = DangKyHoc::where('trang_thai', 'cho_xu_ly')->count();
        
        // Thống kê trạng thái đăng ký học
        $thongKeTrangThai = DangKyHoc::select(
                'trang_thai',
                DB::raw('COUNT(*) as so_luong')
            )
            ->groupBy('trang_thai')
            ->get()
            ->map(function ($item) {
                return [
                    'trang_thai' => ucfirst(str_replace('_', ' ', $item->trang_thai)),
                    'so_luong' => $item->so_luong
                ];
            });
        
        // Thống kê học viên theo khóa học
        $khoaHocStats = KhoaHoc::select(
                'khoa_hocs.id',
                'khoa_hocs.ten',
                DB::raw('COUNT(DISTINCT lop_hocs.id) as so_lop'),
                DB::raw('COUNT(DISTINCT dang_ky_hocs.id) as so_hoc_vien')
            )
            ->leftJoin('lop_hocs', 'lop_hocs.khoa_hoc_id', '=', 'khoa_hocs.id')
            ->leftJoin('dang_ky_hocs', 'dang_ky_hocs.lop_hoc_id', '=', 'lop_hocs.id')
            ->groupBy('khoa_hocs.id', 'khoa_hocs.ten')
            ->having('so_hoc_vien', '>', 0)
            ->get();
        
        // Tính tỷ lệ học viên theo khóa học
        $totalHocVien = $khoaHocStats->sum('so_hoc_vien');
        $khoaHocStats->transform(function ($item) use ($totalHocVien) {
            $item->ty_le = $totalHocVien > 0 ? round(($item->so_hoc_vien / $totalHocVien) * 100, 1) : 0;
            return $item;
        });
        
        // Lấy danh sách học viên mới đăng ký gần đây
        $hocVienMoi = HocVien::with(['nguoiDung', 'lopHoc.khoaHoc'])
            ->orderBy('tao_luc', 'desc')
            ->limit(10)
                                ->get();
        
        // Lấy danh sách các năm để hiển thị select box
        $dsNam = HocVien::selectRaw('YEAR(tao_luc) as nam')
            ->groupBy('nam')
            ->orderBy('nam', 'desc')
            ->pluck('nam')
            ->toArray();
        
        // Truyền dữ liệu biểu đồ vào view để hiển thị
        $duLieuBieuDo = $soLuong;
        
        return view('admin.thong-ke.hoc-vien', compact(
            'labels', 
            'soLuong', 
            'tongHocVien',
            'thongKeTrangThai', 
            'nam', 
            'dsNam',
            'hocVienDangHoc',
            'hocVienMoiThangNay',
            'dangKyChoXuLy',
            'khoaHocStats',
            'hocVienMoi',
            'duLieuBieuDo'
        ));
    }
  
} 