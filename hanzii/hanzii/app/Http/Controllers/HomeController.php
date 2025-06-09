<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\LopHoc;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Hiển thị danh sách tất cả khóa học
     */
    public function allCourses(Request $request)
    {
        // Lọc theo danh mục hoặc từ khóa tìm kiếm
        $query = KhoaHoc::where('trang_thai', 'hoat_dong');
        
        // Tìm kiếm theo từ khóa
        if ($request->has('tu_khoa') && $request->tu_khoa) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function($q) use ($tuKhoa) {
                $q->where('ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('mo_ta', 'like', "%{$tuKhoa}%");
            });
        }
        
        // Lọc theo mức giá
        if ($request->has('gia_min') && $request->gia_min) {
            $query->where('hoc_phi', '>=', $request->gia_min);
        }
        
        if ($request->has('gia_max') && $request->gia_max) {
            $query->where('hoc_phi', '<=', $request->gia_max);
        }
        
        // Sắp xếp
        $sapXep = $request->input('sap_xep', 'moi_nhat');
        switch ($sapXep) {
            case 'gia_tang':
                $query->orderBy('hoc_phi', 'asc');
                break;
            case 'gia_giam':
                $query->orderBy('hoc_phi', 'desc');
                break;
            case 'moi_nhat':
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        $khoaHocs = $query->paginate(12);
        
        return view('all-courses', compact('khoaHocs'));
    }
    
    /**
     * Hiển thị chi tiết khóa học
     */
    public function showCourse($id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        
        // Kiểm tra trạng thái khóa học
        if ($khoaHoc->trang_thai != 'hoat_dong') {
            return redirect()->route('all-courses')
                    ->with('error', 'Khóa học này hiện không khả dụng');
        }
        
        // Lấy các lớp học đang mở đăng ký của khóa học
        $lopHocMo = LopHoc::with('giaoVien.nguoiDung')
                    ->where('khoa_hoc_id', $id)
                    ->where('trang_thai', 'dang_tuyen_sinh')
                    ->where('ngay_bat_dau', '>', now())
                    ->get();
        
        // Lấy các khóa học liên quan cùng danh mục
        $khoaHocLienQuan = KhoaHoc::where('id', '!=', $id)
                            ->where('trang_thai', 'hoat_dong')
                            ->limit(4)
                            ->get();
        
        return view('course-detail', compact(
            'khoaHoc', 
            'lopHocMo', 
            'khoaHocLienQuan'
        ));
    }
} 