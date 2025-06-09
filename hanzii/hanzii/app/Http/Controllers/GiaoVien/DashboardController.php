<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\BaiTapDaNop;
use App\Models\LopHoc;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\BaiTap;
use App\Models\ThongBaoLopHoc;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard của giáo viên
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên. Vui lòng đăng nhập lại.');
        }
        
        try {
            // Lấy thông tin người dùng hiện tại
            $nguoiDung = \App\Models\NguoiDung::findOrFail($nguoiDungId);
            session(['user_full_name' => $nguoiDung->ho . ' ' . $nguoiDung->ten]);
            
            // Lấy danh sách lớp học được phân công
            $lopHocs = LopHoc::where('giao_vien_id', $giaoVien->id)
                        ->whereIn('trang_thai', ['dang_dien_ra', 'dang_hoat_dong'])
                        ->get();
                        
            // Thống kê số học viên
            $tongSoHocVien = HocVien::whereHas('dangKyHocs.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })->count();
            
            // Thống kê số bài tập
            $tongSoBaiTap = BaiTap::whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })->count();
            
            // Thống kê bài nộp chờ chấm
            $baiNopChoGrading = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })->where('trang_thai', 'da_nop')->count();
            
            // Lấy bài nộp gần đây
            $baiNopGanDay = BaiTapDaNop::with(['hocVien.nguoiDung', 'baiTap'])
                ->whereHas('baiTap.baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                    $query->where('giao_vien_id', $giaoVien->id);
                })
                ->where('trang_thai', '!=', 'ban_nhap')
                ->orderBy('cap_nhat_luc', 'desc')
                ->limit(5)
                ->get();
            
            // Lấy yêu cầu tham gia lớp học
            $yeuCauThamGia = \App\Models\DangKyHoc::with(['hocVien.nguoiDung', 'lopHoc'])
                ->whereHas('lopHoc', function($query) use ($giaoVien) {
                    $query->where('giao_vien_id', $giaoVien->id);
                })
                ->where('trang_thai', 'cho_xac_nhan')
                ->orderBy('tao_luc', 'desc')
                ->limit(5)
                ->get();
            
            // Log chi tiết yêu cầu tham gia để debug
            Log::info('Chi tiết đăng ký học chờ xác nhận: ', [
                'so_luong' => $yeuCauThamGia->count(),
                'trang_thai' => 'cho_xac_nhan',
                'giao_vien_id' => $giaoVien->id
            ]);
            
            return view('giao-vien.dashboard', compact(
                'nguoiDung',
                'giaoVien', 
                'lopHocs', 
                'tongSoHocVien', 
                'tongSoBaiTap', 
                'baiNopChoGrading', 
                'baiNopGanDay', 
                'yeuCauThamGia'
            ));
        } catch (\Exception $e) {
            Log::error('Lỗi dashboard giáo viên: ' . $e->getMessage());
            return view('giao-vien.dashboard')->with('error', 'Có lỗi xảy ra khi tải dữ liệu dashboard: ' . $e->getMessage());
        }
    }
       
} 