<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KhoaHocController extends Controller
{
    /**
     * Hiển thị danh sách khóa học có thể đăng ký
     */
    public function index(Request $request)
    {
        // Lọc theo danh mục hoặc từ khóa tìm kiếm
        $query = KhoaHoc::where('trang_thai', 'hoat_dong');
        
        // Lọc theo danh mục
        if ($request->has('danh_muc_id') && $request->danh_muc_id) {
            $query->where('danh_muc_id', $request->danh_muc_id);
        }
        
        // Tìm kiếm theo từ khóa
        if ($request->has('tu_khoa') && $request->tu_khoa) {
            $tuKhoa = $request->tu_khoa;
            $query->where(function($q) use ($tuKhoa) {
                $q->where('ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('mo_ta_ngan', 'like', "%{$tuKhoa}%");
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
        
        return view('hoc-vien.khoa-hoc.index', compact('khoaHocs'));
    }
    
    /**
     * Hiển thị chi tiết khóa học
     */
    public function show($id)
    {
        $khoaHoc = KhoaHoc::findOrFail($id);
        
        // Kiểm tra trạng thái khóa học
        if ($khoaHoc->trang_thai != 'hoat_dong') {
            return redirect()->route('hoc-vien.khoa-hoc.index')
                    ->with('error', 'Khóa học này hiện không khả dụng');
        }
        
        // Lấy các lớp học đang mở đăng ký của khóa học
        $lopHocMo = LopHoc::with('giaoVien.nguoiDung')
                    ->where('khoa_hoc_id', $id)
                    ->where('trang_thai', 'dang_tuyen_sinh')
                    ->where('ngay_bat_dau', '>', now())
                    ->get();
        
        // Kiểm tra học viên đã đăng ký khóa học chưa
        $daDangKy = false;
        if (Auth::check()) {
            $user = Auth::user();
            $hocVien = HocVien::where('user_id', $user->id)->first();
            
            if ($hocVien) {
                $daDangKy = DangKyHoc::whereHas('lopHoc', function($q) use ($id) {
                                $q->where('khoa_hoc_id', $id);
                            })
                            ->where('hoc_vien_id', $hocVien->id)
                            ->exists();
            }
        }
        
        // Lấy các khóa học liên quan cùng danh mục
        $khoaHocLienQuan = KhoaHoc::where('id', '!=', $id)
                            ->where('trang_thai', 'hoat_dong')
                            ->limit(4)
                            ->get();
        
        return view('hoc-vien.khoa-hoc.show', compact(
            'khoaHoc', 
            'lopHocMo', 
            'daDangKy', 
            'khoaHocLienQuan'
        ));
    }
    
    /**
     * Hiển thị danh sách khóa học đã đăng ký
     */
    public function daDangKy()
    {
        $user = Auth::user();
        $hocVien = HocVien::where('user_id', $user->id)->first();
        
        if (!$hocVien) {
            return redirect()->route('hoc-vien.lop-hoc.index')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách khóa học đã đăng ký
        $khoaHocDaDangKy = DB::table('khoa_hocs')
                            ->join('lop_hocs', 'khoa_hocs.id', '=', 'lop_hocs.khoa_hoc_id')
                            ->join('dang_ky_hocs', 'lop_hocs.id', '=', 'dang_ky_hocs.lop_hoc_id')
                            ->select(
                                'khoa_hocs.id',
                                'khoa_hocs.ten',
                                'khoa_hocs.mo_ta_ngan',
                                'khoa_hocs.hinh_anh',
                                DB::raw('COUNT(DISTINCT lop_hocs.id) as so_luong_lop')
                            )
                            ->where('dang_ky_hocs.hoc_vien_id', $hocVien->id)
                            ->where('dang_ky_hocs.trang_thai', 'da_xac_nhan')
                            ->groupBy('khoa_hocs.id', 'khoa_hocs.ten', 'khoa_hocs.mo_ta_ngan', 'khoa_hocs.hinh_anh')
                            ->paginate(9);
        
        return view('hoc-vien.khoa-hoc.da-dang-ky', compact('khoaHocDaDangKy'));
    }
} 