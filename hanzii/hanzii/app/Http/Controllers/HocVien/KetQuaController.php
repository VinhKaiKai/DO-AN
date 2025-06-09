<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaiTapDaNop;
use App\Models\LopHoc;
use App\Models\HocVien;
use App\Models\DangKyHoc;
use Illuminate\Support\Facades\Log;

class KetQuaController extends Controller
{
    /**
     * Hiển thị kết quả học tập của học viên
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại.');
        }
        
        $lopHocId = $request->input('lop_hoc_id');
        
        // Lấy danh sách lớp học của học viên thông qua đăng ký học
        $dangKyHocs = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->whereIn('trang_thai', ['da_xac_nhan'])
            ->pluck('lop_hoc_id')
            ->toArray();
            
        $lopHocs = LopHoc::whereIn('id', $dangKyHocs)
            ->with('khoaHoc')
            ->get();
        
        Log::info('Lớp học IDs: ' . implode(', ', $dangKyHocs));
        
        // Query builder cho các loại bài tập
        $baiTapDaNopQuery = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
            ->with(['baiTap.baiHoc.baiHocLops.lopHoc'])
            ->orderBy('tao_luc', 'desc');
        
        // Lọc theo lớp học nếu có
        if ($lopHocId) {
            $baiTapDaNopQuery->whereHas('baiTap.baiHoc.baiHocLops', function($query) use ($lopHocId) {
                $query->where('lop_hoc_id', $lopHocId);
            });
        }
        
        // Lấy kết quả với phân trang
        $nopBaiTaps = $baiTapDaNopQuery->paginate(10);
        
        // Ghi log số lượng bài tập đã nộp
        Log::info('Số lượng bài tập đã nộp: ' . $nopBaiTaps->total());
        
        // Tính điểm trung bình
        $diemTrungBinh = $this->tinhDiemTrungBinh($hocVien->id, $lopHocId);
        
        return view('hoc-vien.ket-qua.index', compact(
            'nopBaiTaps',
            'lopHocs', 
            'diemTrungBinh',
            'hocVien'
        ));
    }

    /**
     * Hiển thị chi tiết kết quả bài tập
     */
    public function show($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại.');
        }
        
        // Lấy thông tin bài tập đã nộp cụ thể
        $baiTapDaNop = BaiTapDaNop::where('hoc_vien_id', $hocVien->id)
            ->where('id', $id)
            ->with([
                'baiTap',
                'baiTap.baiHoc',
                'baiTap.baiHoc.baiHocLops.lopHoc'
            ])
            ->firstOrFail();
        
        // Xác định loại bài tập để hiển thị view phù hợp
        $loaiBaiTap = $baiTapDaNop->baiTap->loai ?? 'tu_luan';
        
        if ($loaiBaiTap == 'tu_luan') {
            return $this->showTuLuan($baiTapDaNop);
        } elseif ($loaiBaiTap == 'file') {
            return $this->showFile($baiTapDaNop);
        } else {
            return $this->showTuLuan($baiTapDaNop);
        }
    }
    
    
    /**
     * Hiển thị chi tiết bài tập tự luận
     */
    protected function showTuLuan($baiTapDaNop)
    {
        return view('hoc-vien.ket-qua.tu-luan-detail', compact('baiTapDaNop'));
    }
    
    /**
     * Hiển thị chi tiết bài tập file
     */
    protected function showFile($baiTapDaNop)
    {
        return view('hoc-vien.ket-qua.file-detail', compact('baiTapDaNop'));
    }
    
    /**
     * Tính điểm trung bình của học viên
     */
    private function tinhDiemTrungBinh($hocVienId, $lopHocId = null)
    {
        $query = BaiTapDaNop::where('hoc_vien_id', $hocVienId)
            ->where('trang_thai', 'da_cham')
            ->whereNotNull('diem');
        
        // Lọc theo lớp học nếu có
        if ($lopHocId) {
            $query->whereHas('baiTap.baiHoc.baiHocLops', function($q) use ($lopHocId) {
                $q->where('lop_hoc_id', $lopHocId);
            });
        }
        
        // Tính trung bình
        $diemTrungBinh = $query->avg('diem');
        
        return $diemTrungBinh ?: 0;
    }
    
    /**
     * Tải file bài tập đã nộp
     */
    public function download($id)
    {
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin học viên. Vui lòng đăng nhập lại.');
        }
        
        $baiTapDaNop = BaiTapDaNop::where('id', $id)
            ->where('hoc_vien_id', $hocVien->id)
            ->firstOrFail();
            
        if (!$baiTapDaNop->file_path) {
            return back()->with('error', 'Không tìm thấy file đính kèm.');
        }
        
        $filePath = storage_path('app/public/' . $baiTapDaNop->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File không tồn tại trên hệ thống.');
        }
        
        return response()->download($filePath, $baiTapDaNop->ten_file ?? basename($baiTapDaNop->file_path));
    }
} 