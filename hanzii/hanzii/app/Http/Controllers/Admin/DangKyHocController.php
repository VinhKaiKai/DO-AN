<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DangKyHoc;
use App\Models\LopHoc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DangKyHocController extends Controller
{
    /**
     * Xác nhận đăng ký học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function xacNhan($id)
    {
        try {
            DB::beginTransaction();
            
            $dangKyHoc = DangKyHoc::findOrFail($id);
            $lopHoc = LopHoc::findOrFail($dangKyHoc->lop_hoc_id);
            
            // Kiểm tra sĩ số lớp học
            $currentStudents = $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count();
            if ($currentStudents >= $lopHoc->so_luong_toi_da) {
                return back()->with('error', 'Lớp học đã đạt số lượng học viên tối đa.');
            }
            
            // Cập nhật trạng thái đăng ký
            $dangKyHoc->trang_thai = 'da_xac_nhan';
            $dangKyHoc->ngay_tham_gia = now();
            $dangKyHoc->save();
            
            DB::commit();
            
            return back()->with('success', 'Đã xác nhận đăng ký học thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xác nhận đăng ký học: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Từ chối đăng ký học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tuChoi($id)
    {
        try {
            $dangKyHoc = DangKyHoc::findOrFail($id);
            $dangKyHoc->trang_thai = 'da_huy';
            $dangKyHoc->save();
            
            return back()->with('success', 'Đã từ chối đăng ký học thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi từ chối đăng ký học: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 