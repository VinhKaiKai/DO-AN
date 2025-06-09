<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LopHoc;
use App\Models\BaiTap;
use App\Models\LichHoc;
use App\Models\TienDoBaiHoc;
use App\Models\HocVien;
use App\Models\DangKyHoc;
use Carbon\Carbon;
use App\Models\BaiTapDaNop;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard cho học viên
     */
    public function index()
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('welcome')->with('error', 'Không tìm thấy thông tin học viên');
        }
        
        // Lấy danh sách các lớp học viên đang học
        $lopDangHoc = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('trang_thai', 'da_duyet')
            ->with(['lopHoc' => function($query) {
                $query->where('trang_thai', 'dang_hoat_dong');
            }])
            ->whereHas('lopHoc', function($query) {
                $query->where('trang_thai', 'dang_hoat_dong');
            })
            ->get();
        
        return view('hoc-vien.lop-hoc.index', compact('lopDangHoc', 'hocVien'));
    }
 
} 