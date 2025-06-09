<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\TaiLieuBoTro;
use App\Models\GiaoVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaiLieuController extends Controller
{
    /**
     * Tải xuống tài liệu bổ trợ
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên.');
        }
        
        // Lấy thông tin tài liệu và kiểm tra quyền truy cập
        $taiLieu = TaiLieuBoTro::with('baiHoc.baiHocLops.lopHoc')
            ->whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->findOrFail($id);
        
        // Kiểm tra file có tồn tại không
        $filePath = $taiLieu->duong_dan_file;
        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa.');
        }
        
        // Trả về file để tải xuống
        return response()->download(storage_path('app/public/' . $filePath), $taiLieu->tieu_de);
    }
    
    /**
     * Xóa tài liệu bổ trợ
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('login')->with('error', 'Không tìm thấy thông tin giáo viên.');
        }
        
        try {
            // Lấy thông tin tài liệu và kiểm tra quyền truy cập
            $taiLieu = TaiLieuBoTro::with('baiHoc.baiHocLops.lopHoc')
                ->whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                    $query->where('giao_vien_id', $giaoVien->id);
                })
                ->findOrFail($id);
            
            // Xóa file khỏi storage nếu tồn tại
            if ($taiLieu->duong_dan_file && Storage::disk('public')->exists($taiLieu->duong_dan_file)) {
                Storage::disk('public')->delete($taiLieu->duong_dan_file);
            }
            
            // Xóa tài liệu khỏi database
            $taiLieu->delete();
            
            return back()->with('success', 'Đã xóa tài liệu thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa tài liệu: ' . $e->getMessage());
        }
    }
} 