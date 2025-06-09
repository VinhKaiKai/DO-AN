<?php

namespace App\Http\Controllers\TroGiang;

use App\Http\Controllers\Controller;
use App\Models\BaiHoc;
use App\Models\BaiHocLop;
use App\Models\LopHoc;
use App\Models\TroGiang;
use Illuminate\Http\Request;

class BaiHocController extends Controller
{
    /**
     * Hiển thị nội dung bài học chi tiết để trợ giảng có thể bình luận
     *
     * @param  int  $lopHocId ID của lớp học
     * @param  int  $baiHocId ID của bài học
     * @return \Illuminate\Http\Response
     */
    public function show($lopHocId, $baiHocId)
    {
        // Lấy ID người dùng từ session
        $troGiang = TroGiang::where('nguoi_dung_id', session('nguoi_dung_id'))->first();
        
        // Kiểm tra lớp học có phải của trợ giảng này không
        $lopHoc = LopHoc::where('id', $lopHocId)
            ->where('tro_giang_id', $troGiang->id)
            ->first();
            
        if (!$lopHoc) {
            return redirect()->route('tro-giang.lop-hoc.index')
                ->with('error', 'Bạn không có quyền truy cập vào lớp học này');
        }
        
        // Lấy thông tin bài học chi tiết với quan hệ liên quan
        $baiHoc = BaiHoc::with([
            'lopHoc' => function ($query) use ($lopHocId) {
                $query->where('lop_hoc_id', $lopHocId);
            },
            'baiTaps',
            'binhLuans.nguoiDung.vaiTro'
        ])->findOrFail($baiHocId);
        
        // Kiểm tra bài học có thuộc lớp này không
        $baiHocLop = BaiHocLop::where('bai_hoc_id', $baiHocId)
            ->where('lop_hoc_id', $lopHocId)
            ->first();
            
        if (!$baiHocLop) {
            return redirect()->route('tro-giang.lop-hoc.show', $lopHocId)
                ->with('error', 'Bài học không thuộc lớp học này');
        }
        
        // Lấy danh sách bài học của lớp để hiển thị sidebar
        $danhSachBaiHoc = BaiHocLop::where('lop_hoc_id', $lopHocId)
            ->with('baiHoc')
            ->orderBy('so_thu_tu', 'asc')
            ->get();
        
        return view('tro-giang.bai-hoc.show', compact(
            'baiHoc',
            'lopHoc',
            'baiHocLop',
            'danhSachBaiHoc'
        ));
    }
} 