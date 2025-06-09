<?php

namespace App\Http\Controllers\HocVien;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\BaiHoc;
use App\Models\LopHoc;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Lưu bình luận mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'noi_dung' => 'required|string',
            'bai_hoc_id' => 'required|exists:bai_hocs,id',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
        ]);
        
        // Lấy ID người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        $hocVien = HocVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$hocVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin học viên.');
        }
        
        // Kiểm tra học viên đã đăng ký lớp học này chưa
        $dangKyHoc = DangKyHoc::where('hoc_vien_id', $hocVien->id)
            ->where('lop_hoc_id', $request->lop_hoc_id)
            ->whereIn('trang_thai', ['da_xac_nhan'])
            ->first();
            
        if (!$dangKyHoc) {
            return redirect()->back()->with('error', 'Bạn không được phép bình luận trong bài học của lớp bạn chưa đăng ký.');
        }
        
        // Tạo bình luận mới
        $binhLuan = new BinhLuan();
        $binhLuan->nguoi_dung_id = $nguoiDungId;
        $binhLuan->bai_hoc_id = $request->bai_hoc_id;
        $binhLuan->lop_hoc_id = $request->lop_hoc_id;
        $binhLuan->noi_dung = $request->noi_dung;
        $binhLuan->save();
        
        return redirect()->back()->with('success', 'Đã thêm bình luận thành công.');
    }
    
    /**
     * Xóa bình luận
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Lấy ID người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        
        // Tìm bình luận
        $binhLuan = BinhLuan::where('id', $id)
            ->where('nguoi_dung_id', $nguoiDungId)
            ->first();
            
        if (!$binhLuan) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này hoặc bình luận không tồn tại.');
        }
        
        // Xóa bình luận
        $binhLuan->delete();
        
        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }
} 