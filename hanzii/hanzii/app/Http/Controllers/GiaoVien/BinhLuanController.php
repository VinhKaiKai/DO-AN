<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\BaiHoc;
use App\Models\LopHoc;
use App\Models\GiaoVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Hiển thị danh sách bình luận cho giáo viên
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('giao-vien.dashboard')
                ->with('error', 'Không tìm thấy thông tin giáo viên');
        }
        
        // Lấy danh sách lớp học mà giáo viên phụ trách
        $lopHocIds = LopHoc::where('giao_vien_id', $giaoVien->id)->pluck('id')->toArray();
        
        // Filter theo lớp học nếu có
        $lopHocId = $request->input('lop_hoc_id');
        $query = BinhLuan::with(['nguoiDung', 'baiHoc', 'lopHoc'])
            ->whereIn('lop_hoc_id', $lopHocIds)
            ->orderBy('tao_luc', 'desc');
            
        if ($lopHocId && in_array($lopHocId, $lopHocIds)) {
            $query->where('lop_hoc_id', $lopHocId);
        }
        
        $binhLuans = $query->paginate(20);
        
        // Danh sách lớp học để filter
        $lopHocs = LopHoc::where('giao_vien_id', $giaoVien->id)
            ->with('khoaHoc')
            ->get();
            
        return view('giao-vien.binh-luan.index', compact('binhLuans', 'lopHocs', 'lopHocId'));
    }
    
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
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giáo viên.');
        }
        
        // Lấy các lớp giáo viên dạy
        $lopHocIds = LopHoc::where('giao_vien_id', $giaoVien->id)->pluck('id')->toArray();
        
        // Tìm bình luận
        $binhLuan = BinhLuan::where('id', $id)
            ->where(function ($query) use ($nguoiDungId, $lopHocIds) {
                // Hoặc là bình luận của chính giáo viên
                $query->where('nguoi_dung_id', $nguoiDungId)
                // Hoặc là bình luận trong lớp học mà giáo viên dạy
                ->orWhereIn('lop_hoc_id', $lopHocIds);
            })
            ->first();
            
        if (!$binhLuan) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này hoặc bình luận không tồn tại.');
        }
        
        // Xóa bình luận
        $binhLuan->delete();
        
        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }
} 