<?php

namespace App\Http\Controllers\TroGiang;

use App\Http\Controllers\Controller;
use App\Models\BaiTap;
use App\Models\BaiTapDaNop;
use App\Models\TroGiang;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaiTapController extends Controller
{
    /**
     * Hiển thị danh sách bài tập của trợ giảng
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
        
        // Lấy danh sách lớp học mà trợ giảng phụ trách
        $lopHocs = LopHoc::where('tro_giang_id', $troGiang->id)
            ->with('khoaHoc')
            ->get();
            
        // Lấy danh sách bài tập của các lớp học này
        $baiTaps = BaiTap::whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($troGiang) {
                $query->where('tro_giang_id', $troGiang->id);
            })
            ->with(['baiHoc', 'baiTapDaNops'])
            ->orderBy('han_nop', 'desc')
            ->paginate(10);
            
        return view('tro-giang.bai-tap.index', compact('baiTaps', 'lopHocs'));
    }
    
    /**
     * Hiển thị chi tiết bài tập
     */
    public function show($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy thông tin bài tập và kiểm tra quyền truy cập
        $baiTap = BaiTap::whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($troGiang) {
                $query->where('tro_giang_id', $troGiang->id);
            })
            ->with(['baiHoc.baiHocLops.lopHoc'])
            ->findOrFail($id);
            
        // Lấy lớp học từ bài tập
        $lopHoc = $baiTap->baiHoc->baiHocLops->first()->lopHoc;
        
        return view('tro-giang.bai-tap.show', compact('baiTap', 'lopHoc'));
    }
    
    /**
     * Xem danh sách bài tập đã nộp
     */
    public function xemBaiNop($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy thông tin bài tập và kiểm tra quyền truy cập
        $baiTap = BaiTap::whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($troGiang) {
                $query->where('tro_giang_id', $troGiang->id);
            })
            ->with(['baiHoc.baiHocLops.lopHoc'])
            ->findOrFail($id);
            
        // Lấy danh sách bài tập đã nộp
        $baiTapDaNops = BaiTapDaNop::where('bai_tap_id', $id)
            ->with(['hocVien.nguoiDung'])
            ->orderBy('ngay_nop', 'desc')
            ->get();
            
        // Lấy lớp học từ bài tập
        $lopHoc = $baiTap->baiHoc->baiHocLops->first()->lopHoc;
        
        return view('tro-giang.bai-tap.danh-sach-nop', compact('baiTap', 'baiTapDaNops', 'lopHoc'));
    }
    
    /**
     * Hiển thị chi tiết bài tập đã nộp
     */
    public function xemChiTietBaiNop($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy thông tin bài tập đã nộp và kiểm tra quyền truy cập
        $baiTapDaNop = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops.lopHoc', function($query) use ($troGiang) {
                $query->where('tro_giang_id', $troGiang->id);
            })
            ->with(['hocVien.nguoiDung', 'baiTap.baiHoc.baiHocLops.lopHoc'])
            ->findOrFail($id);
            
        // Lấy thông tin bài tập và lớp học
        $baiTap = $baiTapDaNop->baiTap;
        $lopHoc = $baiTap->baiHoc->baiHocLops->first()->lopHoc;
        
        return view('tro-giang.bai-tap.chi-tiet-nop', compact('baiTapDaNop', 'baiTap', 'lopHoc'));
    }
    
    /**
     * Tải xuống tệp bài tập đã nộp
     */
    public function taiFile($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy thông tin bài tập đã nộp và kiểm tra quyền truy cập
        $baiTapDaNop = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops.lopHoc', function($query) use ($troGiang) {
                $query->where('tro_giang_id', $troGiang->id);
            })
            ->findOrFail($id);
            
        // Kiểm tra có tệp đính kèm không
        if (!$baiTapDaNop->file_dinh_kem) {
            return back()->with('error', 'Không tìm thấy tệp đính kèm');
        }
        
        // Trả về tệp đính kèm
        return Storage::disk('public')->download($baiTapDaNop->file_dinh_kem, $baiTapDaNop->ten_file);
    }
} 