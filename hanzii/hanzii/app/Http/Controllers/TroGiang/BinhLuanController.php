<?php

namespace App\Http\Controllers\TroGiang;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\BaiHoc;
use App\Models\LopHoc;
use App\Models\TroGiang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Hiển thị danh sách bình luận cho trợ giảng
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('tro-giang.dashboard')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lấy danh sách lớp học mà trợ giảng phụ trách
        $lopHocIds = LopHoc::where('tro_giang_id', $troGiang->id)->pluck('id')->toArray();
        
        $vaiTro = $request->input('vai_tro', '');
        $baiHocId = $request->input('bai_hoc_id', '');
        $lopHocId = $request->input('lop_hoc_id', '');
        
        $query = BinhLuan::with(['nguoiDung.vaiTro', 'baiHoc', 'phanHois' => function($q) {
                    $q->with('nguoiDung.vaiTro')->orderBy('tao_luc', 'asc');
                }])
                ->whereNull('binh_luan_goc_id')
                ->orderBy('tao_luc', 'asc');
        
        // Lọc theo vai trò
        if ($vaiTro) {
            $query->whereHas('nguoiDung', function($q) use ($vaiTro) {
                $q->where('vai_tro_id', function($q2) use ($vaiTro) {
                    $q2->select('id')
                      ->from('vai_tros')
                      ->where('ten', $vaiTro);
                });
            });
        }
        
        // Filter theo lớp học nếu có
        if ($lopHocId && in_array($lopHocId, $lopHocIds)) {
            $query->where('lop_hoc_id', $lopHocId);
        }
        
        // Filter theo vai trò người dùng nếu có
        if ($vaiTro == 'hoc_vien') {
            $query->whereHas('nguoiDung.vaiTro', function($q) {
                $q->where('ten', 'hoc_vien');
            });
        }
        
        $binhLuans = $query->paginate(20);
        
        // Danh sách lớp học để filter
        $lopHocs = LopHoc::where('tro_giang_id', $troGiang->id)
            ->with('khoaHoc')
            ->get();
            
        return view('tro-giang.binh-luan.index', compact('binhLuans', 'lopHocs', 'lopHocId', 'vaiTro'));
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
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin trợ giảng.');
        }
        
        // Kiểm tra trợ giảng có phụ trách lớp học này không
        $lopHoc = LopHoc::where('id', $request->lop_hoc_id)
                         ->where('tro_giang_id', $troGiang->id)
                         ->first();
                         
        if (!$lopHoc) {
            return redirect()->back()->with('error', 'Bạn không có quyền bình luận trong lớp học này.');
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
    
    /**
     * Phản hồi bình luận của học viên
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function phanHoi(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'noi_dung' => 'required|string',
            'bai_hoc_id' => 'required|exists:bai_hocs,id',
            'lop_hoc_id' => 'required|exists:lop_hocs,id',
            'binh_luan_goc_id' => 'required|exists:binh_luans,id',
        ]);
        
        // Lấy ID người dùng hiện tại
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin trợ giảng.');
        }
        
        // Kiểm tra trợ giảng có phụ trách lớp học này không
        $lopHoc = LopHoc::where('id', $request->lop_hoc_id)
                         ->where('tro_giang_id', $troGiang->id)
                         ->first();
                         
        if (!$lopHoc) {
            return redirect()->back()->with('error', 'Bạn không có quyền phản hồi trong lớp học này.');
        }
        
        // Tạo bình luận mới
        $binhLuan = new BinhLuan();
        $binhLuan->nguoi_dung_id = $nguoiDungId;
        $binhLuan->bai_hoc_id = $request->bai_hoc_id;
        $binhLuan->lop_hoc_id = $request->lop_hoc_id;
        $binhLuan->noi_dung = $request->noi_dung;
        $binhLuan->binh_luan_goc_id = $request->binh_luan_goc_id;
        $binhLuan->save();
        
        // Lấy bình luận gốc và cập nhật trạng thái
        $binhLuanGoc = BinhLuan::find($request->binh_luan_goc_id);
        if ($binhLuanGoc) {
            $binhLuanGoc->da_phan_hoi = true;
            $binhLuanGoc->save();
        }
        
        return redirect()->back()->with('success', 'Đã phản hồi bình luận thành công.');
    }
} 