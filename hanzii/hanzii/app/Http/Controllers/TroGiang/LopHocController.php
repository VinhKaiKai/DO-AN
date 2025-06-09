<?php

namespace App\Http\Controllers\TroGiang;

use App\Http\Controllers\Controller;
use App\Models\BaiHocLop;
use App\Models\BaiTap;
use App\Models\BaiTapDaNop;
use App\Models\BinhLuan;
use App\Models\DangKyHoc;
use App\Models\LopHoc;
use App\Models\TroGiang;
use App\Models\TienDoBaiHoc;
use Illuminate\Http\Request;

class LopHocController extends Controller
{
    /**
     * Hiển thị danh sách lớp học
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        // Lọc theo trạng thái lớp học (nếu có)
        $trangThai = $request->input('trang_thai');
        
        // Lấy danh sách lớp học được phân công
        $query = LopHoc::where('tro_giang_id', $troGiang->id)
            ->with(['khoaHoc', 'giaoVien.nguoiDung'])
            ->withCount(['dangKyHocs' => function ($query) {
                $query->whereIn('trang_thai', ['dang_hoc', 'da_duyet']);
            }]);
            
        // Lọc theo trạng thái nếu có
        if ($trangThai) {
            $query->where('trang_thai', $trangThai);
        }
        
        $lopHocs = $query->orderBy('ngay_bat_dau', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        // Kiểm tra các lớp học có bình luận cần phản hồi không
        $lopHocIdsCanPhanHoi = $this->getLopHocIdsCanPhanHoi($troGiang->id);
            
        return view('tro-giang.lop-hoc.index', compact('lopHocs', 'trangThai', 'lopHocIdsCanPhanHoi'));
    }

    /**
     * Lấy danh sách ID các lớp học có bình luận cần phản hồi
     * 
     * @param int $troGiangId ID của trợ giảng
     * @return array Mảng chứa các ID lớp học có bình luận cần phản hồi
     */
    private function getLopHocIdsCanPhanHoi($troGiangId)
    {
        // Lấy danh sách lớp học mà trợ giảng phụ trách
        $lopHocIds = LopHoc::where('tro_giang_id', $troGiangId)->pluck('id')->toArray();
        
        // Lấy danh sách bình luận chưa được phản hồi của học viên trong các lớp đó
        $lopHocIdsCanPhanHoi = BinhLuan::whereIn('lop_hoc_id', $lopHocIds)
            ->where('da_phan_hoi', false)
            ->whereHas('nguoiDung.vaiTro', function ($query) {
                $query->where('ten', 'hoc_vien');
            })
            ->pluck('lop_hoc_id')
            ->unique()
            ->toArray();
            
        return $lopHocIdsCanPhanHoi;
    }

    /**
     * Hiển thị chi tiết lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        
        // Kiểm tra lớp học có phải của trợ giảng này không
        $lopHoc = LopHoc::where('id', $id)
            ->where('tro_giang_id', $troGiang->id)
            ->with([
                'khoaHoc',
                'giaoVien.nguoiDung',
                'baiHocLops' => function ($query) {
                    $query->orderBy('so_thu_tu', 'asc');
                },
                'baiHocLops.baiHoc',
            ])
            ->withCount(['dangKyHocs', 'baiHocLops', 'baiTaps'])
            ->first();
            
        if (!$lopHoc) {
            return redirect()->route('tro-giang.lop-hoc.index')
                ->with('error', 'Bạn không có quyền truy cập vào lớp học này');
        }
        
        // Lấy danh sách học viên của lớp
        $hocViens = DangKyHoc::where('lop_hoc_id', $id)
            ->whereIn('trang_thai', ['dang_hoc', 'da_duyet'])
            ->with('hocVien.nguoiDung')
            ->get();
        
        // Tính tỷ lệ hoàn thành khóa học
        $tongBaiHoc = $lopHoc->bai_hoc_lops_count;
        $soLuongHoanThanh = 0;
        
        if ($tongBaiHoc > 0) {
            // Đếm tổng số bài học đã hoàn thành của tất cả học viên
            $tongSoBaiHocHoanThanh = TienDoBaiHoc::whereHas('baiHoc.baiHocLops', function ($query) use ($id) {
                    $query->where('lop_hoc_id', $id);
                })
                ->where('trang_thai', 'da_hoan_thanh')
                ->count();
            
            // Số học viên
            $soHocVien = $hocViens->count() > 0 ? $hocViens->count() : 1;
            
            // Tính trung bình
            $soLuongHoanThanh = $tongSoBaiHocHoanThanh / $soHocVien;
        }
        
        $hoanThanhTyLe = $tongBaiHoc > 0 ? round(($soLuongHoanThanh / $tongBaiHoc) * 100) : 0;
        
        // Tính điểm trung bình bài tập
        $diemTrungBinh = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops', function ($query) use ($id) {
                $query->where('lop_hoc_id', $id);
            })
            ->where('trang_thai', 'da_cham')
            ->avg('diem');
        
        $diemTrungBinh = $diemTrungBinh ? number_format($diemTrungBinh, 1) : 'N/A';
        
        // Đếm số bài tập đã nộp và chưa nộp
        $baiTapDaNop_count = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops', function ($query) use ($id) {
                $query->where('lop_hoc_id', $id);
            })
            ->whereIn('trang_thai', ['da_nop', 'da_cham'])
            ->count();
        
        // Số bài tập chưa nộp = Tổng số bài tập * số học viên - Số bài tập đã nộp
        $tongSoBaiTap = BaiTap::whereHas('baiHoc.baiHocLops', function ($query) use ($id) {
                $query->where('lop_hoc_id', $id);
            })
            ->count();
        
        $baiTapChuaNop_count = ($tongSoBaiTap * $hocViens->count()) - $baiTapDaNop_count;
        $baiTapChuaNop_count = $baiTapChuaNop_count > 0 ? $baiTapChuaNop_count : 0;
        
        // Lấy danh sách bài tập gần đây cần chấm điểm
        $baiTapGanDay = BaiTapDaNop::whereHas('baiTap.baiHoc.baiHocLops', function ($query) use ($id) {
                $query->where('lop_hoc_id', $id);
            })
            ->where('trang_thai', 'da_nop')
            ->with(['hocVien.nguoiDung', 'baiTap'])
            ->orderBy('ngay_nop', 'desc')
            ->limit(5)
            ->get();
            
        return view('tro-giang.lop-hoc.show', compact(
            'lopHoc',
            'hocViens',
            'hoanThanhTyLe',
            'diemTrungBinh',
            'baiTapDaNop_count',
            'baiTapChuaNop_count',
            'baiTapGanDay'
        ));
    }

    /**
     * Hiển thị danh sách học viên của lớp
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function danhSachHocVien($id)
    {
        $troGiang = auth()->user()->troGiang;
        $lopHoc = LopHoc::where('id', $id)
            ->where('tro_giang_id', $troGiang->id)
            ->firstOrFail();
        
        // Lấy danh sách học viên của lớp
        $hocViens = DangKyHoc::where('lop_hoc_id', $id)
            ->where('trang_thai', 'dang_hoc')
            ->whereHas('hocVien.nguoiDung', function ($query) {
                $query->where('vai_tro_id', function($q) {
                    $q->select('id')
                      ->from('vai_tros')
                      ->where('ten', 'hoc_vien');
                });
            })
            ->with(['hocVien.nguoiDung'])
            ->get();
        
        // Lấy thống kê tiến độ học tập
        $tienDoHocTap = [];
        foreach ($hocViens as $dangKy) {
            $hocVienId = $dangKy->hoc_vien_id;
            
            // Lấy tổng số bài học trong lớp
            $tongBaiHoc = BaiHocLop::where('lop_hoc_id', $id)->count();
            
            // Lấy số bài học đã hoàn thành
            $soLuongHoanThanh = TienDoBaiHoc::whereHas('baiHoc.baiHocLops', function ($query) use ($id) {
                    $query->where('lop_hoc_id', $id);
                })
                ->where('hoc_vien_id', $hocVienId)
                ->where('trang_thai', 'da_hoan_thanh')
                ->count();
                
            // Tính phần trăm hoàn thành
            $phanTramHoanThanh = $tongBaiHoc > 0 ? round(($soLuongHoanThanh / $tongBaiHoc) * 100) : 0;
            
            $tienDoHocTap[$hocVienId] = [
                'tong_bai_hoc' => $tongBaiHoc,
                'da_hoan_thanh' => $soLuongHoanThanh,
                'phan_tram' => $phanTramHoanThanh
            ];
        }
        
        return view('tro-giang.lop-hoc.danh-sach-hoc-vien', compact(
            'lopHoc', 
            'hocViens', 
            'tienDoHocTap'
        ));
    }

    /**
     * Hiển thị danh sách bài tập của lớp học
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function danhSachBaiTap($id)
    {
        // Lấy ID người dùng từ session
        $nguoiDungId = session('nguoi_dung_id');
        $troGiang = TroGiang::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$troGiang) {
            return redirect()->route('login')
                ->with('error', 'Không tìm thấy thông tin trợ giảng');
        }
        
        
        // Lấy thông tin lớp học
        $lopHoc = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung'])->findOrFail($id);
        
        // Lấy danh sách bài tập của lớp học
        $baiTaps = BaiTap::whereHas('baiHoc.baiHocLops', function ($query) use ($id) {
                $query->where('lop_hoc_id', $id);
            })
            ->with([
                'baiHoc',
                'baiTapDaNops' => function ($query) {
                    $query->whereIn('trang_thai', ['da_nop', 'da_cham']);
                }
            ])
            ->orderBy('han_nop', 'desc')
            ->get();
        
        // Thống kê số lượng bài đã nộp, đã chấm và chưa chấm
        foreach ($baiTaps as $baiTap) {
            $baiTap->so_luong_nop = $baiTap->baiTapDaNops->count();
            $baiTap->so_luong_cham = $baiTap->baiTapDaNops->where('trang_thai', 'da_cham')->count();
            $baiTap->so_luong_chua_cham = $baiTap->baiTapDaNops->where('trang_thai', 'da_nop')->count();
        }
        
        return view('tro-giang.lop-hoc.danh-sach-bai-tap', compact('lopHoc', 'baiTaps'));
    }
} 