<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LopHoc;
use App\Models\ThanhToanLuong;
use App\Models\ThanhToanHocPhi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThanhToanLuongController extends Controller
{
    /**
     * Hiển thị danh sách lớp học đã kết thúc để quản lý thanh toán lương.
     */
    public function index(Request $request)
    {
        // Xây dựng query
        $query = LopHoc::where('trang_thai', 'da_ket_thuc');
        
        // Lọc theo tên lớp hoặc mã lớp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('ma_lop', 'like', "%{$search}%");
            });
        }
        
        // Lấy danh sách lớp học
        $lopHocs = $query->orderBy('tao_luc', 'desc')->get();
            
        // Lấy thông tin thanh toán lương cho từng lớp
        foreach ($lopHocs as $lopHoc) {
            $thanhToanLuong = ThanhToanLuong::where('lop_hoc_id', $lopHoc->id)->first();
            
            if (!$thanhToanLuong) {
                // Nếu chưa có bản ghi thanh toán lương, tính tổng tiền thu từ học phí
                $tongTienThu = ThanhToanHocPhi::where('lop_hoc_id', $lopHoc->id)
                    ->where('trang_thai', 'da_thanh_toan')
                    ->sum('so_tien');
                
                // Tạo bản ghi thanh toán lương mới
                $thanhToanLuong = ThanhToanLuong::create([
                    'lop_hoc_id' => $lopHoc->id,
                    'giao_vien_id' => $lopHoc->giao_vien_id,
                    'tro_giang_id' => $lopHoc->tro_giang_id,
                    'tong_tien_thu' => $tongTienThu,
                ]);
            }
            
            $lopHoc->thanhToanLuong = $thanhToanLuong;
        }
        
        // Lọc theo trạng thái thanh toán
        if ($request->filled('trang_thai')) {
            $trangThai = $request->trang_thai;
            
            if ($trangThai === 'da_thanh_toan') {
                $lopHocs = $lopHocs->filter(function($lopHoc) {
                    return $lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->daThanhToanDayDu();
                });
            } else if ($trangThai === 'chua_thanh_toan') {
                $lopHocs = $lopHocs->filter(function($lopHoc) {
                    return !($lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->daThanhToanDayDu());
                });
            }
        }
        
        // Thống kê tổng quan
        $tongLopDaKetThuc = $lopHocs->count();
        $tongLopDaTraLuong = $lopHocs->filter(function($lopHoc) {
            return $lopHoc->thanhToanLuong && $lopHoc->thanhToanLuong->daThanhToanDayDu();
        })->count();
        $tongLopChuaTraLuong = $tongLopDaKetThuc - $tongLopDaTraLuong;
        
        return view('admin.thanh-toan-luong.index', compact(
            'lopHocs',
            'tongLopDaKetThuc',
            'tongLopDaTraLuong',
            'tongLopChuaTraLuong'
        ));
    }

    /**
     * Hiển thị chi tiết thanh toán lương của một lớp học.
     */
    public function show(string $id)
    {
        $lopHoc = LopHoc::with(['khoaHoc', 'giaoVien', 'troGiang'])->findOrFail($id);
        
        // Lấy hoặc tạo thông tin thanh toán lương
        $thanhToanLuong = ThanhToanLuong::firstOrNew(['lop_hoc_id' => $id]);
        
        if (!$thanhToanLuong->exists) {
            // Nếu chưa có bản ghi thanh toán lương, tính tổng tiền thu từ học phí
            $tongTienThu = ThanhToanHocPhi::where('lop_hoc_id', $id)
                ->where('trang_thai', 'da_thanh_toan')
                ->sum('so_tien');
            
            // Tạo bản ghi thanh toán lương mới
            $thanhToanLuong->fill([
                'giao_vien_id' => $lopHoc->giao_vien_id,
                'tro_giang_id' => $lopHoc->tro_giang_id,
                'tong_tien_thu' => $tongTienThu,
            ]);
            $thanhToanLuong->save();
        }
        
        return view('admin.thanh-toan-luong.show', compact('lopHoc', 'thanhToanLuong'));
    }

    /**
     * Cập nhật thông tin thanh toán lương.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'he_so_luong_giao_vien' => 'required|numeric|min:0|max:100',
            'he_so_luong_tro_giang' => 'required|numeric|min:0|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            $thanhToanLuong = ThanhToanLuong::findOrFail($id);
            
            // Cập nhật hệ số lương
            $thanhToanLuong->he_so_luong_giao_vien = $request->he_so_luong_giao_vien;
            $thanhToanLuong->he_so_luong_tro_giang = $request->he_so_luong_tro_giang;
            
            // Tính lương dựa trên hệ số
            $thanhToanLuong->tien_luong_giao_vien = ($thanhToanLuong->tong_tien_thu * $thanhToanLuong->he_so_luong_giao_vien) / 100;
            $thanhToanLuong->tien_luong_tro_giang = ($thanhToanLuong->tong_tien_thu * $thanhToanLuong->he_so_luong_tro_giang) / 100;
            
            $thanhToanLuong->save();
            
            DB::commit();
            
            return redirect()->route('admin.thanh-toan-luong.show', $thanhToanLuong->lop_hoc_id)
                ->with('success', 'Cập nhật hệ số lương thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật hệ số lương: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật hệ số lương');
        }
    }

    /**
     * Cập nhật trạng thái thanh toán lương cho giáo viên.
     */
    public function updateGiaoVienStatus(Request $request, $id)
    {
        $request->validate([
            'trang_thai_giao_vien' => 'required|in:chua_thanh_toan,da_thanh_toan',
        ]);
        
        try {
            DB::beginTransaction();
            
            $thanhToanLuong = ThanhToanLuong::findOrFail($id);
            $thanhToanLuong->trang_thai_giao_vien = $request->trang_thai_giao_vien;
            
            if ($request->trang_thai_giao_vien == 'da_thanh_toan' && !$thanhToanLuong->ngay_thanh_toan_giao_vien) {
                $thanhToanLuong->ngay_thanh_toan_giao_vien = now();
            }
            
            $thanhToanLuong->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán lương giáo viên thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật trạng thái thanh toán lương giáo viên: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái thanh toán lương giáo viên');
        }
    }

    /**
     * Cập nhật trạng thái thanh toán lương cho trợ giảng.
     */
    public function updateTroGiangStatus(Request $request, $id)
    {
        $request->validate([
            'trang_thai_tro_giang' => 'required|in:chua_thanh_toan,da_thanh_toan',
        ]);
        
        try {
            DB::beginTransaction();
            
            $thanhToanLuong = ThanhToanLuong::findOrFail($id);
            $thanhToanLuong->trang_thai_tro_giang = $request->trang_thai_tro_giang;
            
            if ($request->trang_thai_tro_giang == 'da_thanh_toan' && !$thanhToanLuong->ngay_thanh_toan_tro_giang) {
                $thanhToanLuong->ngay_thanh_toan_tro_giang = now();
            }
            
            $thanhToanLuong->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán lương trợ giảng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật trạng thái thanh toán lương trợ giảng: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái thanh toán lương trợ giảng');
        }
    }

    /**
     * Hoàn thành thanh toán lương.
     */
    public function complete($id)
    {
        try {
            DB::beginTransaction();
            
            $thanhToanLuong = ThanhToanLuong::findOrFail($id);
            
            // Kiểm tra xem cả giáo viên và trợ giảng đã được trả lương chưa
            if ($thanhToanLuong->trang_thai_giao_vien !== 'da_thanh_toan' || 
                $thanhToanLuong->trang_thai_tro_giang !== 'da_thanh_toan') {
                return redirect()->back()->with('error', 'Vui lòng thanh toán đầy đủ lương cho giáo viên và trợ giảng trước khi hoàn thành');
            }
            
            DB::commit();
            
            return redirect()->route('admin.thanh-toan-luong.index')
                ->with('success', 'Hoàn thành thanh toán lương thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi hoàn thành thanh toán lương: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi hoàn thành thanh toán lương');
        }
    }
}
