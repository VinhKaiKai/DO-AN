<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyHoc;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\TroGiang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LopHocExport;

class LopHocController extends Controller
{
    /**
     * Hiển thị danh sách lớp học
     */
    public function index(Request $request)
    {
        // Debug: Kiểm tra tổng số lớp học trong database
        $totalLopHoc = LopHoc::count();

        $query = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung'])
                ->withCount(['dangKyHocs' => function($query) {
                    $query->where('trang_thai', 'da_xac_nhan');
                }]);
        
        // Lọc theo tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('ten', 'like', '%'.$request->search.'%')
                  ->orWhere('ma_lop', 'like', '%'.$request->search.'%');
            });
        }
        
        // Lọc theo khóa học
        if ($request->has('khoa_hoc_id') && !empty($request->khoa_hoc_id)) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('trang_thai') && !empty($request->trang_thai) && $request->trang_thai != 'tat_ca') {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        // Sắp xếp
        $query->orderBy('tao_luc', 'desc');
        
        // Debug: Kiểm tra SQL query được tạo ra
        $sqlWithBindings = vsprintf(str_replace('?', "'%s'", $query->toSql()), $query->getBindings());
        Log::info('SQL Query: ' . $sqlWithBindings);

        $lopHocs = $query->paginate(10);
        
        // Lấy danh sách khóa học cho bộ lọc
        $khoaHocs = KhoaHoc::where('trang_thai', 'hoat_dong')->get();
        
        // Thống kê số lượng
        $tong_lop = LopHoc::count();
        $dang_dien_ra = LopHoc::where('trang_thai', 'dang_dien_ra')->orWhere('trang_thai', 'dang_dien_ra')->count();
        $sap_khai_giang = LopHoc::where('trang_thai', 'sap_khai_giang')->count();
        $da_ket_thuc = LopHoc::where('trang_thai', 'da_ket_thuc')->count();
        
        // Thống kê thêm
        $tong_hoc_vien = HocVien::count();
        $tong_giao_vien = GiaoVien::whereHas('nguoiDung', function($query) {
            $query->where('vai_tro_id', function($q) {
                $q->select('id')
                  ->from('vai_tros')
                  ->where('ten', 'giao_vien');
            });
        })->count();
        
        return view('admin.lop-hoc.index', compact(
            'lopHocs', 
            'khoaHocs', 
            'tong_lop', 
            'dang_dien_ra', 
            'sap_khai_giang', 
            'da_ket_thuc',
            'tong_hoc_vien',
            'tong_giao_vien'
        ));
    }

    /**
     * Hiển thị form tạo lớp học mới
     */
    public function create(Request $request)
    {
        // Lấy tất cả khóa học để hiển thị
        $khoaHocs = KhoaHoc::orderBy('ten')->pluck('ten', 'id');
        
        // Debug: Kiểm tra số lượng khóa học
        $countKhoaHoc = KhoaHoc::count();
        $firstKhoaHoc = KhoaHoc::first();
        
        // Lấy danh sách giáo viên
        $giaoViens = GiaoVien::whereHas('nguoiDung', function ($query) {
            $query->where('vai_tro_id', function($q) {
                $q->select('id')
                  ->from('vai_tros')
                  ->where('ten', 'giao_vien');
            });
        })->with('nguoiDung')
          ->get()
          ->pluck('nguoiDung.ho_ten', 'id');
        
        // Lấy danh sách trợ giảng
        $troGiangs = TroGiang::whereHas('nguoiDung', function ($query) {
            $query->where('vai_tro_id', function($q) {
                $q->select('id')
                  ->from('vai_tros')
                  ->where('ten', 'tro_giang');
            });
        })->with('nguoiDung')
          ->get()
          ->pluck('nguoiDung.ho_ten', 'id');
        
        // Nếu có khóa học được chọn trước
        $selectedKhoaHoc = null;
        if ($request->has('khoa_hoc_id') && !empty($request->khoa_hoc_id)) {
            $selectedKhoaHoc = KhoaHoc::find($request->khoa_hoc_id);
        }
        
        return view('admin.lop-hoc.create', compact('khoaHocs', 'giaoViens', 'troGiangs', 'selectedKhoaHoc', 'countKhoaHoc', 'firstKhoaHoc'));
    }

    /**
     * Lưu lớp học mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:50|unique:lop_hocs',
            'khoa_hoc_id' => 'required|exists:khoa_hocs,id',
            'giao_vien_id' => 'required|exists:giao_viens,id',
            'tro_giang_id' => 'required|exists:tro_giangs,id',
            'hinh_thuc_hoc' => 'required|in:online,offline',
            'lich_hoc' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:sap_khai_giang,dang_dien_ra,da_ket_thuc',
            'so_luong_toi_da' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tạo lớp học mới
        $lopHoc = LopHoc::create([
            'ten' => $request->ten,
            'ma_lop' => $request->ma_lop,
            'khoa_hoc_id' => $request->khoa_hoc_id,
            'giao_vien_id' => $request->giao_vien_id,
            'tro_giang_id' => $request->tro_giang_id,
            'hinh_thuc_hoc' => $request->hinh_thuc_hoc,
            'lich_hoc' => $request->lich_hoc,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'trang_thai' => $request->trang_thai,
            'so_luong_toi_da' => $request->so_luong_toi_da,
        ]);

        return redirect()->route('admin.lop-hoc.show', $lopHoc->id)
                ->with('success', 'Tạo lớp học mới thành công!');
    }

    /**
     * Hiển thị chi tiết lớp học
     */
    public function show($id)
    {
        $lopHoc = LopHoc::with([
                'khoaHoc', 
                'giaoVien.nguoiDung', 
                'troGiang.nguoiDung',
                'dangKyHocs.hocVien.nguoiDung',
                'baiHocLops.baiHoc'
            ])
            ->findOrFail($id);
        
        // Đếm số yêu cầu tham gia đang chờ duyệt
        $countPendingRequests = DangKyHoc::where('lop_hoc_id', $id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->count();
        
        return view('admin.lop-hoc.show', compact('lopHoc', 'countPendingRequests'));
    }

    /**
     * Hiển thị form sửa lớp học
     */
    public function edit($id)
    {
        $lopHoc = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung', 'dangKyHocs'])->findOrFail($id);
        
        $khoaHocs = KhoaHoc::orderBy('ten')->pluck('ten', 'id');
        
        $countKhoaHoc = KhoaHoc::count();
        
        // Lấy danh sách giáo viên
        $giaoViens = GiaoVien::whereHas('nguoiDung', function ($query) {
            $query->where('vai_tro_id', function($q) {
                $q->select('id')
                  ->from('vai_tros')
                  ->where('ten', 'giao_vien');
            });
        })->with('nguoiDung')
          ->get()
          ->pluck('nguoiDung.ho_ten', 'id');
        
        // Lấy danh sách trợ giảng
        $troGiangs = TroGiang::whereHas('nguoiDung', function ($query) {
            $query->where('vai_tro_id', function($q) {
                $q->select('id')
                  ->from('vai_tros')
                  ->where('ten', 'tro_giang');
            });
        })->with('nguoiDung')
          ->get()
          ->pluck('nguoiDung.ho_ten', 'id');
        
        return view('admin.lop-hoc.edit', compact('lopHoc', 'khoaHocs', 'giaoViens', 'troGiangs', 'countKhoaHoc'));
    }

    /**
     * Cập nhật thông tin lớp học
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:50|unique:lop_hocs,ma_lop,'.$id,
            'khoa_hoc_id' => 'required|exists:khoa_hocs,id',
            'giao_vien_id' => 'required|exists:giao_viens,id',
            'tro_giang_id' => 'required|exists:tro_giangs,id',
            'hinh_thuc_hoc' => 'required|in:online,offline',
            'lich_hoc' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:sap_khai_giang,dang_dien_ra,da_ket_thuc',
            'so_luong_toi_da' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $lopHoc = LopHoc::findOrFail($id);

        // Kiểm tra số lượng học viên hiện tại
        $currentStudents = $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count();
        if ($currentStudents > $request->so_luong_toi_da) {
            return back()->withErrors(['so_luong_toi_da' => 'Số lượng tối đa không thể nhỏ hơn số học viên hiện tại ('.$currentStudents.' học viên)'])->withInput();
        }

        // Cập nhật thông tin
        $lopHoc->update([
            'ten' => $request->ten,
            'ma_lop' => $request->ma_lop,
            'khoa_hoc_id' => $request->khoa_hoc_id,
            'giao_vien_id' => $request->giao_vien_id,
            'tro_giang_id' => $request->tro_giang_id,
            'hinh_thuc_hoc' => $request->hinh_thuc_hoc,
            'lich_hoc' => $request->lich_hoc,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'trang_thai' => $request->trang_thai,
            'so_luong_toi_da' => $request->so_luong_toi_da,
        ]);

        return redirect()->route('admin.lop-hoc.show', $lopHoc->id)
                ->with('success', 'Cập nhật lớp học thành công!');
    }

    /**
     * Xóa lớp học
     */
    public function destroy($id)
    {
        $lopHoc = LopHoc::findOrFail($id);

        // Kiểm tra đăng ký học
        $dangKyHocCount = DangKyHoc::where('lop_hoc_id', $id)->count();
        if ($dangKyHocCount > 0) {
            return back()->with('error', 'Không thể xóa lớp học đã có học viên đăng ký!');
        }
        
        // Xóa lớp học
        $lopHoc->delete();

        return redirect()->route('admin.lop-hoc.index')
                ->with('success', 'Xóa lớp học thành công!');
    }
    
    /**
     * Xuất danh sách lớp học ra Excel
     */
    public function export(Request $request) 
    {
        $fileName = 'danh_sach_lop_hoc_' . date('Y-m-d') . '.xlsx';
        
        // Lọc dữ liệu theo request nếu cần
        $query = LopHoc::with(['khoaHoc', 'giaoVien.nguoiDung', 'troGiang.nguoiDung']);
        
        if ($request->has('khoa_hoc_id') && !empty($request->khoa_hoc_id)) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }
        
        if ($request->has('trang_thai') && !empty($request->trang_thai)) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        $lopHocs = $query->get();
        
        return Excel::download(new LopHocExport($lopHocs), $fileName);
    }
    
    /**
     * Hiển thị danh sách học viên của lớp học
     */
    public function danhSachHocVien($id)
    {
        $lopHoc = LopHoc::with([
            'khoaHoc',
            'giaoVien.nguoiDung',
            'troGiang.nguoiDung',
            'dangKyHocs.hocVien.nguoiDung',
        ])->findOrFail($id);
        
        // Lấy tất cả đăng ký học của lớp
        $dangKyHocs = $lopHoc->dangKyHocs;
        
        // Phân loại học viên theo trạng thái
        $confirmedStudents = $dangKyHocs->where('trang_thai', 'da_xac_nhan');
        $pendingStudents = $dangKyHocs->where('trang_thai', 'cho_xac_nhan');
        
        // Lấy danh sách học viên chưa đăng ký lớp này để thêm
        $registeredStudentIds = $dangKyHocs->pluck('hoc_vien_id')->toArray();
        $availableStudents = HocVien::whereNotIn('id', $registeredStudentIds)
            ->with('nguoiDung')
            ->get();
        
        return view('admin.lop-hoc.danh-sach-hoc-vien', compact(
            'lopHoc', 
            'dangKyHocs',
            'confirmedStudents', 
            'pendingStudents', 
            'availableStudents'
        ));
    }
    
    /**
     * Thêm học viên vào lớp học
     */
    public function addStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hoc_vien_id' => 'required|exists:hoc_viens,id',
            'hoc_phi' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $lopHoc = LopHoc::findOrFail($id);
        
        // Kiểm tra sĩ số tối đa của lớp
        $currentStudents = $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count();
        
        if ($currentStudents >= $lopHoc->so_luong_toi_da) {
            return back()->with('error', 'Lớp học đã đạt sĩ số tối đa (' . $lopHoc->so_luong_toi_da . ' học viên)');
        }
        
        // Kiểm tra học viên đã đăng ký chưa
        $exists = DangKyHoc::where('lop_hoc_id', $id)
            ->where('hoc_vien_id', $request->hoc_vien_id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Học viên này đã đăng ký lớp học!');
        }
        
        // Tạo đăng ký mới
        DangKyHoc::create([
            'lop_hoc_id' => $id,
            'hoc_vien_id' => $request->hoc_vien_id,
            'hoc_phi' => $request->hoc_phi,
            'ngay_dang_ky' => now(),
            'trang_thai' => 'da_xac_nhan',
            'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan ?? 'chuyen_khoan',
            'ghi_chu' => $request->ghi_chu,
        ]);
        
        return redirect()->route('admin.lop-hoc.danh-sach-hoc-vien', $id)
            ->with('success', 'Thêm học viên vào lớp thành công!');
    }
    
    /**
     * Xóa học viên khỏi lớp học
     */
    public function removeStudent($lopHocId, $dangKyId)
    {
        $dangKy = DangKyHoc::where('id', $dangKyId)
            ->where('lop_hoc_id', $lopHocId)
            ->firstOrFail();
        
        $dangKy->delete();
        
        return redirect()->route('admin.lop-hoc.danh-sach-hoc-vien', $lopHocId)
            ->with('success', 'Đã xóa học viên khỏi lớp học!');
    }
    
    /**
     * Hiển thị danh sách yêu cầu tham gia lớp học
     */
    public function danhSachYeuCauThamGia($id)
    {
        $lopHoc = LopHoc::with(['khoaHoc'])->findOrFail($id);
        
        $yeuCauThamGia = DangKyHoc::where('lop_hoc_id', $id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->with('hocVien.nguoiDung')
            ->paginate(10);
        
        return view('admin.lop-hoc.yeu-cau-tham-gia', compact(
            'lopHoc',
            'yeuCauThamGia'
        ));
    }
    
    /**
     * Duyệt yêu cầu tham gia
     */
    public function duyetYeuCauThamGia($lopHocId, $yeuCauId)
    {
        $dangKyHoc = DangKyHoc::findOrFail($yeuCauId);
        
        // Kiểm tra sĩ số tối đa
        $lopHoc = LopHoc::findOrFail($lopHocId);
        $currentStudents = $lopHoc->dangKyHocs()->where('trang_thai', 'da_xac_nhan')->count();
        
        if ($currentStudents >= $lopHoc->so_luong_toi_da) {
            return back()->with('error', 'Lớp học đã đạt số lượng học viên tối đa (' . $lopHoc->so_luong_toi_da . ' học viên)');
        }
        
        // Cập nhật trạng thái đăng ký học
        $dangKyHoc->update([
            'trang_thai' => 'da_xac_nhan',
            'ngay_tham_gia' => now(),
        ]);
        
        return redirect()->route('admin.lop-hoc.yeu-cau-tham-gia', $lopHocId)
            ->with('success', 'Đã duyệt yêu cầu tham gia lớp học thành công!');
    }
    
    /**
     * Từ chối yêu cầu tham gia
     */
    public function tuChoiYeuCauThamGia($lopHocId, $yeuCauId)
    {
        $dangKyHoc = DangKyHoc::findOrFail($yeuCauId);
        
        $dangKyHoc->update([
            'trang_thai' => 'bi_tu_choi',
            'ghi_chu' => 'Lý do từ chối: ' . request('ly_do_tu_choi'),
        ]);
        
        // Chuyển hướng về trang danh sách học viên của lớp thay vì trang yêu cầu tham gia
        return redirect()->route('admin.lop-hoc.danh-sach-hoc-vien', $lopHocId)
            ->with('success', 'Đã từ chối yêu cầu tham gia lớp học!');
    }
} 