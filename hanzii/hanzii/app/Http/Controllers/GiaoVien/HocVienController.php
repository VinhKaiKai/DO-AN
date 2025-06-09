<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoc;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\BaiTapDaNop;
use App\Models\TienDoBaiHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GiaoVien;

class HocVienController extends Controller
{
    /**
     * Hiển thị danh sách học viên của giáo viên
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Lấy tham số lọc
        $lopHocId = $request->input('lop_hoc_id');
        $trangThai = $request->input('trang_thai');
        
        // Query cơ bản
        $query = HocVien::with(['nguoiDung', 'dangKyHocs.lopHoc'])
            ->whereHas('dangKyHocs.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            });
            
        // Lọc theo lớp học nếu có
        if ($lopHocId) {
            $query->whereHas('dangKyHocs', function($query) use ($lopHocId) {
                $query->where('lop_hoc_id', $lopHocId);
            });
        }
        
        // Lọc theo trạng thái nếu có
        if ($trangThai) {
            $query->whereHas('dangKyHocs', function($query) use ($trangThai) {
                $query->where('trang_thai', $trangThai);
            });
        }
        
        // Lấy danh sách học viên
        $hocViens = $query->orderBy('id', 'desc')->paginate(20);
        
        // Lấy danh sách lớp học của giáo viên
        $lopHocs = LopHoc::where('giao_vien_id', $giaoVien->id)
            ->with('khoaHoc')
            ->orderBy('ten', 'asc')
            ->get();
            
        return view('giao-vien.hoc-vien.index', compact('hocViens', 'lopHocs', 'lopHocId', 'trangThai'));
    }

    /**
     * Hiển thị thông tin chi tiết học viên
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        // Lấy thông tin học viên và kiểm tra quyền truy cập
        $hocVien = HocVien::with(['nguoiDung'])
            ->whereHas('dangKyHocs.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->findOrFail($id);
            
        // Lấy danh sách lớp học đã đăng ký
        $dangKyHocs = DangKyHoc::with(['lopHoc.khoaHoc', 'lopHoc.giaoVien.nguoiDung'])
            ->where('hoc_vien_id', $id)
            ->whereHas('lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->orderBy('ngay_dang_ky', 'desc')
            ->get();
            
            
        // Lấy thông tin bài tập đã nộp
        $baiTapDaNops = BaiTapDaNop::with(['baiTap', 'baiTap.baiHoc.baiHocLops.lopHoc'])
            ->where('hoc_vien_id', $id)
            ->whereHas('baiTap.baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->orderBy('thoi_gian_nop', 'desc')
            ->get();
            
        // Tính điểm trung bình
        $diemTrungBinh = 0;
        $soBaiDaCham = 0;
        
        foreach ($baiTapDaNops as $baiTapDaNop) {
            if ($baiTapDaNop->diem !== null) {
                $diemTrungBinh += $baiTapDaNop->diem;
                $soBaiDaCham++;
            }
        }
        
        $diemTrungBinh = $soBaiDaCham > 0 ? round($diemTrungBinh / $soBaiDaCham, 1) : null;
        
        // Lấy thông tin tiến độ bài học
        $tienDoBaiHocs = TienDoBaiHoc::with(['baiHoc.baiHocLops.lopHoc'])
            ->where('hoc_vien_id', $id)
            ->whereHas('baiHoc.baiHocLops.lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->orderBy('ngay_cap_nhat', 'desc')
            ->get();
            
        return view('giao-vien.hoc-vien.show', compact(
            'hocVien', 
            'dangKyHocs', 
            'baiTapDaNops', 
            'diemTrungBinh', 
            'soBaiDaCham',
            'tienDoBaiHocs'
        ));
    }

    /**
     * Xác nhận đăng ký học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function xacNhan($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        try {
            DB::beginTransaction();
            
            // Lấy thông tin đăng ký học và kiểm tra quyền truy cập
            $dangKyHoc = DangKyHoc::whereHas('lopHoc', function($query) use ($giaoVien) {
                    $query->where('giao_vien_id', $giaoVien->id);
                })
                ->findOrFail($id);
                
            // Xác nhận đăng ký học và cập nhật các trường cần thiết
            $dangKyHoc->trang_thai = 'da_xac_nhan';
            $dangKyHoc->ngay_tham_gia = now();
            
            // Thêm ngày đăng ký nếu chưa có
            if (!$dangKyHoc->ngay_dang_ky) {
                $dangKyHoc->ngay_dang_ky = now();
            }
            
            $dangKyHoc->save();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Đã xác nhận học viên tham gia lớp học thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xác nhận đăng ký: ' . $e->getMessage());
        }
    }
} 