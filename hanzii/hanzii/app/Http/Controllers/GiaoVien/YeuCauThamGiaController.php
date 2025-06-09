<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YeuCauThamGia;
use App\Models\GiaoVien;
use App\Models\LopHoc;
use App\Models\DangKyHoc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class YeuCauThamGiaController extends Controller
{
    /**
     * Hiển thị danh sách các yêu cầu tham gia
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Lấy ID người dùng đang đăng nhập và thông tin giáo viên
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin giáo viên');
        }
        
        // Lọc theo trạng thái nếu có
        $trangThai = $request->input('trang_thai', 'all');
        
        // Lấy danh sách lớp học mà giáo viên đang phụ trách
        $lopHocIds = LopHoc::where('giao_vien_id', $giaoVien->id)
            ->pluck('id')
            ->toArray();
        
        // Lấy danh sách yêu cầu tham gia
        $query = YeuCauThamGia::whereIn('lop_hoc_id', $lopHocIds)
            ->with(['hocVien.nguoiDung', 'lopHoc']);
        
        // Lọc theo trạng thái nếu được chỉ định
        if ($trangThai !== 'all') {
            $query->where('trang_thai', $trangThai);
        }
        
        // Sắp xếp và phân trang
        $yeuCaus = $query->orderBy('ngay_gui', 'desc')
            ->paginate(10);
        
        // Thống kê nhanh
        $tongYeuCau = YeuCauThamGia::whereIn('lop_hoc_id', $lopHocIds)->count();
        $yeuCauChoDuyet = YeuCauThamGia::whereIn('lop_hoc_id', $lopHocIds)
            ->where('trang_thai', 'cho_xac_nhan')
            ->count();
        $yeuCauDaDuyet = YeuCauThamGia::whereIn('lop_hoc_id', $lopHocIds)
            ->where('trang_thai', 'da_duyet')
            ->count();
        $yeuCauTuChoi = YeuCauThamGia::whereIn('lop_hoc_id', $lopHocIds)
            ->where('trang_thai', 'tu_choi')
            ->count();
        
        return view('giao-vien.yeu-cau-tham-gia.index', compact(
            'yeuCaus',
            'trangThai',
            'tongYeuCau',
            'yeuCauChoDuyet',
            'yeuCauDaDuyet',
            'yeuCauTuChoi'
        ));
    }

    /**
     * Hiển thị chi tiết yêu cầu tham gia
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin giáo viên');
        }
        
        // Lấy thông tin yêu cầu và kiểm tra quyền truy cập
        $yeuCau = YeuCauThamGia::with(['hocVien.nguoiDung', 'lopHoc'])
            ->whereHas('lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->findOrFail($id);
        
        return view('giao-vien.yeu-cau-tham-gia.show', compact('yeuCau'));
    }

    /**
     * Duyệt yêu cầu tham gia
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function duyet($id, Request $request)
    {
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin giáo viên');
        }
        
        // Lấy thông tin yêu cầu
        $yeuCau = YeuCauThamGia::whereHas('lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->findOrFail($id);
        
        // Kiểm tra trạng thái yêu cầu
        if ($yeuCau->trang_thai !== 'cho_xac_nhan') {
            return redirect()->back()
                ->with('error', 'Yêu cầu này đã được xử lý trước đó');
        }
        
        // Kiểm tra sĩ số lớp học
        $lopHoc = $yeuCau->lopHoc;
        $currentStudents = $lopHoc->dangKyHocs()->whereIn('trang_thai', ['da_xac_nhan'])->count();
        
     
        
        // Thực hiện trong transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();
        
        try {
            // Cập nhật trạng thái yêu cầu
            $yeuCau->update([
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $nguoiDungId,
                'ngay_duyet' => now()
            ]);
            
            // Tạo đăng ký học cho học viên
            DangKyHoc::create([
                'lop_hoc_id' => $yeuCau->lop_hoc_id,
                'hoc_vien_id' => $yeuCau->hoc_vien_id,
                'ngay_dang_ky' => now(),
                'trang_thai' => 'da_xac_nhan',
                'hoc_phi' => $lopHoc->khoaHoc->hoc_phi,
                'ghi_chu' => 'Được duyệt từ yêu cầu tham gia lớp học'
            ]);
            
            DB::commit();
            
            return redirect()->route('giao-vien.yeu-cau-tham-gia.index')
                ->with('success', 'Đã duyệt yêu cầu tham gia lớp học thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi duyệt yêu cầu: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối yêu cầu tham gia
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
        public function tuChoi(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500'
        ]);
        
        $nguoiDungId = $request->session()->get('nguoi_dung_id');
        $giaoVien = GiaoVien::where('nguoi_dung_id', $nguoiDungId)->first();
        
        if (!$giaoVien) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin giáo viên');
        }
        
        // Lấy thông tin yêu cầu
        $yeuCau = YeuCauThamGia::whereHas('lopHoc', function($query) use ($giaoVien) {
                $query->where('giao_vien_id', $giaoVien->id);
            })
            ->findOrFail($id);
        
        // Kiểm tra trạng thái yêu cầu
        if ($yeuCau->trang_thai !== 'cho_xac_nhan') {
            return redirect()->back()
                ->with('error', 'Yêu cầu này đã được xử lý trước đó');
        }
        
        // Cập nhật trạng thái yêu cầu
        $yeuCau->update([
            'trang_thai' => 'tu_choi',
            'nguoi_duyet_id' => $nguoiDungId,
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'ngay_duyet' => now()
        ]);
        
        return redirect()->route('giao-vien.yeu-cau-tham-gia.index')
            ->with('success', 'Đã từ chối yêu cầu tham gia lớp học');
    }
} 