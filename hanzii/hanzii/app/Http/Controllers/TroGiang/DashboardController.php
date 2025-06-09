<?php

namespace App\Http\Controllers\TroGiang;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\DangKyHoc;
use App\Models\LopHoc;
use App\Models\TroGiang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard cho trợ giảng
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy danh sách lớp học được phân công
        $lopHocs = LopHoc::where('tro_giang_id', $troGiang->id)
            ->with(['khoaHoc', 'giaoVien.nguoiDung'])
            ->withCount(['dangKyHocs' => function ($query) {
                $query->whereIn('trang_thai', ['dang_hoc', 'da_duyet']);
            }])
            ->orderBy('ngay_bat_dau', 'desc')
            ->limit(5)
            ->get();
            
        // Kiểm tra các lớp học có bình luận cần phản hồi không
        $lopHocIdsCanPhanHoi = $this->getLopHocIdsCanPhanHoi($troGiang->id);
           
        // Thống kê tổng học viên đang giảng dạy
        $lopHocIds = LopHoc::where('tro_giang_id', $troGiang->id)->pluck('id');
        $hocVienCount = DangKyHoc::whereIn('lop_hoc_id', $lopHocIds)
            ->where('trang_thai', 'dang_hoc')
            ->whereHas('hocVien.nguoiDung', function ($query) {
                $query->where('vai_tro_id', function($q) {
                    $q->select('id')
                      ->from('vai_tros')
                      ->where('ten', 'hoc_vien');
                });
            })
            ->count();
            
        return view('tro-giang.dashboard', compact('lopHocs', 'lopHocIdsCanPhanHoi', 'hocVienCount'));
    }
    
    /**
     * Lấy danh sách ID các lớp học có bình luận cần phản hồi
     * 
     * @param int $troGiangId ID của trợ giảng
     * @return array Mảng chứa các ID lớp học có bình luận cần phản hồi
     */
    private function getLopHocIdsCanPhanHoi($troGiangId)
    {
        // Lấy danh sách lớp học mà trợ giảng phụ trách
        $lopHocIds = LopHoc::where('tro_giang_id', $troGiangId)->pluck('id')->toArray();
        
        // Lấy danh sách bình luận chưa được phản hồi của học viên trong các lớp đó
        $lopHocIdsCanPhanHoi = BinhLuan::whereIn('lop_hoc_id', $lopHocIds)
            ->where('da_phan_hoi', false)
            ->whereHas('nguoiDung.vaiTro', function ($query) {
                $query->where('ten', 'hoc_vien');
            })
            ->pluck('lop_hoc_id')
            ->unique()
            ->toArray();
            
        return $lopHocIdsCanPhanHoi;
    }
} 